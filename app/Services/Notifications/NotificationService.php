<?php

namespace App\Services\Notifications;

use App\Models\User;
use App\Models\Notification;
use App\Events\NotificationEvent;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class NotificationService
{
    // =====================================================
    // SEND NOTIFICATION — multi-channel dispatcher
    // =====================================================

    public function send(array $params): Notification
    {
        $notification = Notification::create([
            'user_id' => $params['user_id'] ?? null,
            'client_id' => $params['client_id'] ?? null,
            'type' => $params['type'], // lead_new, case_status, task_due, doc_expiry, payment, system
            'title' => $params['title'],
            'body' => $params['body'],
            'data' => $params['data'] ?? [],
            'channels' => $params['channels'] ?? ['in_app'],
            'priority' => $params['priority'] ?? 'normal',
            'status' => 'pending',
        ]);

        $channels = $params['channels'] ?? ['in_app'];
        $results = [];

        foreach ($channels as $channel) {
            try {
                $results[$channel] = match ($channel) {
                    'in_app' => $this->sendInApp($notification),
                    'email' => $this->sendEmail($notification, $params),
                    'sms' => $this->sendSMS($notification, $params),
                    'whatsapp' => $this->sendWhatsApp($notification, $params),
                    'telegram' => $this->sendTelegram($notification, $params),
                    'push' => $this->sendPush($notification, $params),
                    default => false,
                };
            } catch (\Exception $e) {
                $results[$channel] = false;
                Log::error("Notification [{$channel}] failed: {$e->getMessage()}");
            }
        }

        $allSuccess = !in_array(false, $results, true);
        $notification->update([
            'status' => $allSuccess ? 'sent' : 'partial',
            'sent_at' => now(),
            'channel_results' => $results,
        ]);

        return $notification;
    }

    // =====================================================
    // IN-APP (WebSocket + DB)
    // =====================================================

    protected function sendInApp(Notification $notification): bool
    {
        if ($notification->user_id) {
            broadcast(new NotificationEvent($notification->user_id, [
                'id' => $notification->id,
                'type' => $notification->type,
                'title' => $notification->title,
                'body' => Str::limit($notification->body, 200),
                'priority' => $notification->priority,
                'created_at' => $notification->created_at->toIso8601String(),
            ]));
        }
        return true;
    }

    // =====================================================
    // EMAIL (SendGrid / SMTP)
    // =====================================================

    protected function sendEmail(Notification $notification, array $params): bool
    {
        $to = $params['email'] ?? null;
        if (!$to && $notification->user_id) {
            $to = User::find($notification->user_id)?->email;
        }
        if (!$to) return false;

        $template = $params['email_template'] ?? 'default';
        $templateData = array_merge([
            'title' => $notification->title,
            'body' => $notification->body,
            'action_url' => $params['action_url'] ?? null,
            'action_text' => $params['action_text'] ?? 'Open in CRM',
        ], $params['data'] ?? []);

        Mail::send("emails.notifications.{$template}", $templateData, function ($msg) use ($to, $notification) {
            $msg->to($to)->subject($notification->title);
        });

        return true;
    }

    // =====================================================
    // SMS (Twilio / SMSApi.pl)
    // =====================================================

    protected function sendSMS(Notification $notification, array $params): bool
    {
        $phone = $params['phone'] ?? null;
        if (!$phone) return false;

        $provider = config('services.sms.provider', 'smsapi');
        $message = Str::limit("{$notification->title}: {$notification->body}", 160);

        if ($provider === 'smsapi') {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . config('services.smsapi.token'),
            ])->post('https://api.smsapi.pl/sms.do', [
                'to' => preg_replace('/[^0-9+]/', '', $phone),
                'message' => $message,
                'from' => 'WinCase',
                'encoding' => 'utf-8',
                'format' => 'json',
            ]);
            return $response->successful();
        }

        // Twilio fallback
        $response = Http::withBasicAuth(
            config('services.twilio.sid'),
            config('services.twilio.token')
        )->post("https://api.twilio.com/2010-04-01/Accounts/" . config('services.twilio.sid') . "/Messages.json", [
            'To' => $phone,
            'From' => config('services.twilio.from'),
            'Body' => $message,
        ]);

        return $response->successful();
    }

    // =====================================================
    // WHATSAPP (Business API / Cloud API)
    // =====================================================

    protected function sendWhatsApp(Notification $notification, array $params): bool
    {
        $phone = $params['phone'] ?? null;
        if (!$phone) return false;

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . config('services.whatsapp.token'),
            'Content-Type' => 'application/json',
        ])->post(config('services.whatsapp.api_url') . '/' . config('services.whatsapp.phone_id') . '/messages', [
            'messaging_product' => 'whatsapp',
            'to' => preg_replace('/[^0-9]/', '', $phone),
            'type' => 'template',
            'template' => [
                'name' => $params['wa_template'] ?? 'notification_general',
                'language' => ['code' => $params['language'] ?? 'pl'],
                'components' => [
                    [
                        'type' => 'body',
                        'parameters' => [
                            ['type' => 'text', 'text' => $notification->title],
                            ['type' => 'text', 'text' => Str::limit($notification->body, 300)],
                        ],
                    ],
                ],
            ],
        ]);

        return $response->successful();
    }

    // =====================================================
    // TELEGRAM BOT
    // =====================================================

    protected function sendTelegram(Notification $notification, array $params): bool
    {
        $chatId = $params['telegram_chat_id'] ?? config('services.telegram.admin_chat_id');
        if (!$chatId) return false;

        $emoji = match ($notification->priority) {
            'urgent' => '🚨', 'high' => '🔴', 'normal' => '📬', 'low' => '📝', default => '📬',
        };

        $text = "{$emoji} <b>{$notification->title}</b>\n\n{$notification->body}";

        if (!empty($params['action_url'])) {
            $text .= "\n\n<a href=\"{$params['action_url']}\">Open in CRM →</a>";
        }

        $response = Http::post("https://api.telegram.org/bot" . config('services.telegram.bot_token') . "/sendMessage", [
            'chat_id' => $chatId,
            'text' => $text,
            'parse_mode' => 'HTML',
            'disable_web_page_preview' => true,
        ]);

        return $response->successful();
    }

    // =====================================================
    // PUSH (Firebase FCM)
    // =====================================================

    protected function sendPush(Notification $notification, array $params): bool
    {
        $user = $notification->user_id ? User::find($notification->user_id) : null;
        $fcmToken = $params['fcm_token'] ?? $user?->fcm_token;
        if (!$fcmToken) return false;

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->getFCMAccessToken(),
            'Content-Type' => 'application/json',
        ])->post('https://fcm.googleapis.com/v1/projects/' . config('services.firebase.project_id') . '/messages:send', [
            'message' => [
                'token' => $fcmToken,
                'notification' => [
                    'title' => $notification->title,
                    'body' => Str::limit($notification->body, 200),
                ],
                'data' => array_map('strval', [
                    'type' => $notification->type,
                    'notification_id' => (string) $notification->id,
                    'action_url' => $params['action_url'] ?? '',
                ]),
                'android' => ['priority' => $notification->priority === 'urgent' ? 'high' : 'normal'],
                'apns' => ['payload' => ['aps' => ['sound' => 'default', 'badge' => 1]]],
            ],
        ]);

        return $response->successful();
    }

    protected function getFCMAccessToken(): string
    {
        return cache()->remember('fcm_access_token', 3500, function () {
            $credentials = json_decode(file_get_contents(config('services.firebase.credentials_path')), true);
            $now = time();
            $header = $this->base64url(json_encode(['alg' => 'RS256', 'typ' => 'JWT']));
            $claim = $this->base64url(json_encode([
                'iss' => $credentials['client_email'],
                'scope' => 'https://www.googleapis.com/auth/firebase.messaging',
                'aud' => 'https://oauth2.googleapis.com/token',
                'iat' => $now, 'exp' => $now + 3600,
            ]));
            openssl_sign("{$header}.{$claim}", $sig, $credentials['private_key'], 'sha256');
            $jwt = "{$header}.{$claim}." . $this->base64url($sig);

            $resp = Http::asForm()->post('https://oauth2.googleapis.com/token', [
                'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                'assertion' => $jwt,
            ]);
            return $resp->json('access_token');
        });
    }

    protected function base64url(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    // =====================================================
    // BULK SEND — same notification to multiple users
    // =====================================================

    public function sendBulk(array $userIds, array $params): array
    {
        $results = ['sent' => 0, 'failed' => 0];
        foreach ($userIds as $uid) {
            $params['user_id'] = $uid;
            $n = $this->send($params);
            $n->status === 'sent' ? $results['sent']++ : $results['failed']++;
        }
        return $results;
    }

    // =====================================================
    // USER NOTIFICATIONS — list + mark read
    // =====================================================

    public function getUserNotifications(int $userId, int $limit = 30): array
    {
        return Notification::where('user_id', $userId)
            ->orderByDesc('created_at')
            ->limit($limit)
            ->get()
            ->toArray();
    }

    public function getUnreadCount(int $userId): int
    {
        return Notification::where('user_id', $userId)
            ->whereNull('read_at')
            ->count();
    }

    public function markRead(int $userId, ?int $notificationId = null): int
    {
        $query = Notification::where('user_id', $userId)->whereNull('read_at');
        if ($notificationId) $query->where('id', $notificationId);
        return $query->update(['read_at' => now()]);
    }

    // =====================================================
    // NOTIFICATION TEMPLATES
    // =====================================================

    public static function templates(): array
    {
        return [
            'lead_new' => [
                'title' => 'New Lead: {name}',
                'body' => 'New lead from {source}: {name} ({phone}). Service: {service_type}.',
                'channels' => ['in_app', 'push', 'telegram'],
                'priority' => 'high',
            ],
            'lead_unassigned' => [
                'title' => 'Unassigned Lead Alert',
                'body' => 'Lead {name} has been unassigned for {hours}h. Please assign a manager.',
                'channels' => ['in_app', 'push', 'telegram'],
                'priority' => 'urgent',
            ],
            'case_status_changed' => [
                'title' => 'Case {case_number} → {status}',
                'body' => 'Case {case_number} for {client_name} changed to {status}.',
                'channels' => ['in_app', 'push'],
                'priority' => 'normal',
            ],
            'case_deadline' => [
                'title' => '⏰ Case Deadline: {case_number}',
                'body' => 'Case {case_number} deadline in {days} days ({deadline_date}).',
                'channels' => ['in_app', 'push', 'email', 'telegram'],
                'priority' => 'high',
            ],
            'task_assigned' => [
                'title' => 'New Task: {title}',
                'body' => 'Task "{title}" assigned to you. Due: {due_date}.',
                'channels' => ['in_app', 'push'],
                'priority' => 'normal',
            ],
            'task_overdue' => [
                'title' => '⚠️ Overdue Task: {title}',
                'body' => 'Task "{title}" is overdue (due: {due_date}).',
                'channels' => ['in_app', 'push', 'email'],
                'priority' => 'high',
            ],
            'document_expiring' => [
                'title' => 'Document Expiring: {doc_type}',
                'body' => '{client_name}\'s {doc_type} expires in {days} days ({expiry_date}).',
                'channels' => ['in_app', 'push', 'email', 'whatsapp'],
                'priority' => 'high',
            ],
            'payment_received' => [
                'title' => '💰 Payment: {amount} PLN',
                'body' => 'Payment {amount} PLN received from {client_name} via {method}.',
                'channels' => ['in_app', 'telegram'],
                'priority' => 'normal',
            ],
            'payment_pending' => [
                'title' => 'Payment Pending Approval',
                'body' => '{amount} PLN from {client_name} awaiting approval.',
                'channels' => ['in_app', 'push'],
                'priority' => 'normal',
            ],
            'client_message' => [
                'title' => 'Message from {client_name}',
                'body' => 'New message via {channel}: "{message_preview}"',
                'channels' => ['in_app', 'push'],
                'priority' => 'normal',
            ],
            'system_alert' => [
                'title' => '🔧 System: {title}',
                'body' => '{body}',
                'channels' => ['in_app', 'telegram'],
                'priority' => 'high',
            ],
            'news_published' => [
                'title' => '📰 Published: {title}',
                'body' => 'Article published to {site}: "{title}" [{language}]',
                'channels' => ['in_app'],
                'priority' => 'low',
            ],
        ];
    }
}

// ---------------------------------------------------------------
// Аннотация (RU):
// NotificationService — 6 каналов: In-App (WebSocket), Email (SendGrid/SMTP),
// SMS (SMSApi.pl/Twilio), WhatsApp (Business Cloud API), Telegram Bot, Push (Firebase FCM v1).
// 12 шаблонов: lead_new, lead_unassigned, case_status, case_deadline, task_assigned,
// task_overdue, doc_expiring, payment_received/pending, client_message, system_alert, news_published.
// sendBulk() — массовая рассылка. getUserNotifications() + markRead().
// Priority: urgent/high/normal/low. channel_results JSON.
// Файл: app/Services/Notifications/NotificationService.php
// ---------------------------------------------------------------
