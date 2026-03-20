<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\Notifications\NotificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function __construct(protected NotificationService $notif) {}

    // GET /notifications — current user's notifications
    public function index(Request $request): JsonResponse
    {
        $limit = min((int) $request->get('limit', 30), 100);
        return response()->json(['success' => true, 'data' => [
            'notifications' => $this->notif->getUserNotifications($request->user()->id, $limit),
            'unread_count' => $this->notif->getUnreadCount($request->user()->id),
        ]]);
    }

    // POST /notifications/read — mark all as read
    public function markAllRead(Request $request): JsonResponse
    {
        $count = $this->notif->markRead($request->user()->id);
        return response()->json(['success' => true, 'data' => ['marked' => $count]]);
    }

    // POST /notifications/{id}/read — mark single as read
    public function markRead(Request $request, int $id): JsonResponse
    {
        $this->notif->markRead($request->user()->id, $id);
        return response()->json(['success' => true]);
    }

    // GET /notifications/unread-count
    public function unreadCount(Request $request): JsonResponse
    {
        return response()->json(['success' => true, 'data' => [
            'count' => $this->notif->getUnreadCount($request->user()->id),
        ]]);
    }

    // POST /notifications/send — send custom notification (admin)
    public function send(Request $request): JsonResponse
    {
        $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'type' => 'required|string',
            'title' => 'required|string|max:200',
            'body' => 'required|string|max:2000',
            'channels' => 'required|array',
            'channels.*' => 'in:in_app,email,sms,whatsapp,telegram,push',
        ]);

        $notification = $this->notif->send($request->all());
        return response()->json(['success' => true, 'data' => $notification->toArray()]);
    }

    // POST /notifications/send-bulk — bulk send (admin)
    public function sendBulk(Request $request): JsonResponse
    {
        $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
            'type' => 'required|string',
            'title' => 'required|string|max:200',
            'body' => 'required|string|max:2000',
            'channels' => 'required|array',
        ]);

        $results = $this->notif->sendBulk($request->input('user_ids'), $request->except('user_ids'));
        return response()->json(['success' => true, 'data' => $results]);
    }

    // POST /notifications/register-device — save FCM token
    public function registerDevice(Request $request): JsonResponse
    {
        $request->validate([
            'fcm_token' => 'required|string|max:512',
            'platform' => 'required|in:android,ios,web',
        ]);

        $request->user()->update([
            'fcm_token' => $request->input('fcm_token'),
            'device_platform' => $request->input('platform'),
        ]);

        return response()->json(['success' => true, 'message' => 'Device registered']);
    }

    // GET /notifications/templates
    public function templates(): JsonResponse
    {
        return response()->json(['success' => true, 'data' => NotificationService::templates()]);
    }
}
