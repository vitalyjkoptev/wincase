<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SettingsController extends Controller
{
    // ─────────────────────────────────────────
    // GENERAL SETTINGS
    // ─────────────────────────────────────────

    public function general(Request $request): JsonResponse
    {
        $data = $request->only([
            'company_name', 'company_nip', 'company_address', 'company_phone',
            'company_email', 'default_language', 'timezone', 'currency',
        ]);

        foreach ($data as $key => $value) {
            DB::table('settings')->updateOrInsert(
                ['key' => $key],
                ['value' => $value, 'updated_at' => now()]
            );
        }

        return response()->json(['success' => true, 'message' => 'General settings saved.']);
    }

    public function getGeneral(): JsonResponse
    {
        $settings = DB::table('settings')
            ->pluck('value', 'key')
            ->toArray();

        return response()->json(['success' => true, 'data' => $settings]);
    }

    // ─────────────────────────────────────────
    // EMAIL TEMPLATES
    // ─────────────────────────────────────────

    public function emailTemplates(Request $request): JsonResponse
    {
        $request->validate([
            'key' => 'required|string',
            'lang' => 'required|string|max:5',
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
        ]);

        DB::table('email_templates')->updateOrInsert(
            ['key' => $request->input('key'), 'lang' => $request->input('lang')],
            [
                'subject' => $request->input('subject'),
                'body' => $request->input('body'),
                'updated_at' => now(),
            ]
        );

        return response()->json(['success' => true, 'message' => 'Template saved.']);
    }

    public function emailTemplateTest(Request $request): JsonResponse
    {
        $request->validate([
            'key' => 'required|string',
            'email' => 'required|email',
        ]);

        // Placeholder — actual send via Mail facade when SMTP configured
        return response()->json([
            'success' => true,
            'message' => 'Test email queued to ' . $request->input('email'),
        ]);
    }

    public function emailTemplateToggle(Request $request): JsonResponse
    {
        $request->validate([
            'key' => 'required|string',
            'enabled' => 'required|boolean',
        ]);

        DB::table('email_templates')
            ->where('key', $request->input('key'))
            ->update(['is_active' => $request->boolean('enabled'), 'updated_at' => now()]);

        return response()->json(['success' => true, 'message' => 'Template toggled.']);
    }

    // ─────────────────────────────────────────
    // NOTIFICATIONS (admin)
    // ─────────────────────────────────────────

    public function notifications(Request $request): JsonResponse
    {
        $data = $request->validate([
            'notification' => 'required|string',
            'channel' => 'required|string|in:email,telegram,sms,push',
            'enabled' => 'required|boolean',
        ]);

        DB::table('notification_settings')->updateOrInsert(
            ['notification' => $data['notification'], 'channel' => $data['channel']],
            ['enabled' => $data['enabled'], 'updated_at' => now()]
        );

        return response()->json(['success' => true, 'message' => 'Notification setting saved.']);
    }

    public function notificationsBulk(Request $request): JsonResponse
    {
        $settings = $request->validate([
            'settings' => 'required|array',
            'settings.*.notification' => 'required|string',
            'settings.*.channel' => 'required|string',
            'settings.*.enabled' => 'required|boolean',
        ]);

        foreach ($settings['settings'] as $s) {
            DB::table('notification_settings')->updateOrInsert(
                ['notification' => $s['notification'], 'channel' => $s['channel']],
                ['enabled' => $s['enabled'], 'updated_at' => now()]
            );
        }

        return response()->json(['success' => true, 'message' => 'All notification settings saved.']);
    }

    // ─────────────────────────────────────────
    // INTEGRATIONS
    // ─────────────────────────────────────────

    public function integrations(): JsonResponse
    {
        $rows = DB::table('settings')
            ->where('key', 'like', 'intg_%')
            ->pluck('value', 'key')
            ->toArray();

        $data = [];
        foreach ($rows as $key => $val) {
            $service = substr($key, 5); // remove "intg_"
            $decoded = json_decode($val, true);
            $data[$service] = is_array($decoded) ? $decoded : ['value' => $val];
        }

        return response()->json(['success' => true, 'data' => $data]);
    }

    public function integrationConnect(Request $request): JsonResponse
    {
        $request->validate([
            'service' => 'required|string|max:50',
        ]);

        $service = $request->input('service');
        $fields = $request->except(['service', '_token', '_method']);

        DB::table('settings')->updateOrInsert(
            ['key' => 'intg_' . $service],
            [
                'group' => 'integrations',
                'value' => json_encode($fields),
                'updated_at' => now(),
            ]
        );

        return response()->json(['success' => true, 'message' => $service . ' saved.']);
    }

    public function integrationDisconnect(Request $request): JsonResponse
    {
        $request->validate(['service' => 'required|string']);

        DB::table('settings')
            ->where('key', 'intg_' . $request->input('service'))
            ->delete();

        return response()->json(['success' => true, 'message' => $request->input('service') . ' disconnected.']);
    }

    public function integrationsSaveAll(Request $request): JsonResponse
    {
        $items = $request->validate([
            'integrations' => 'required|array',
            'integrations.*.service' => 'required|string|max:50',
        ]);

        foreach ($items['integrations'] as $item) {
            $service = $item['service'];
            $fields = $item;
            unset($fields['service']);

            // Skip empty items
            $hasData = false;
            foreach ($fields as $v) {
                if (!empty($v)) { $hasData = true; break; }
            }
            if (!$hasData) continue;

            DB::table('settings')->updateOrInsert(
                ['key' => 'intg_' . $service],
                [
                    'group' => 'integrations',
                    'value' => json_encode($fields),
                    'updated_at' => now(),
                ]
            );
        }

        return response()->json(['success' => true, 'message' => 'All integrations saved.']);
    }

    // ─────────────────────────────────────────
    // STAFF PREFERENCES
    // ─────────────────────────────────────────

    public function staffNotifications(Request $request): JsonResponse
    {
        $user = $request->user();

        if ($request->isMethod('get')) {
            $prefs = DB::table('user_notification_prefs')
                ->where('user_id', $user->id)
                ->pluck('enabled', 'notification')
                ->toArray();

            return response()->json(['success' => true, 'data' => $prefs]);
        }

        // POST
        $data = $request->validate([
            'notification' => 'required|string',
            'channel' => 'required|string',
            'enabled' => 'required|boolean',
        ]);

        DB::table('user_notification_prefs')->updateOrInsert(
            ['user_id' => $user->id, 'notification' => $data['notification'], 'channel' => $data['channel']],
            ['enabled' => $data['enabled'], 'updated_at' => now()]
        );

        return response()->json(['success' => true, 'message' => 'Staff notification saved.']);
    }

    public function staffNotificationsBulk(Request $request): JsonResponse
    {
        $user = $request->user();
        $settings = $request->validate([
            'settings' => 'required|array',
            'settings.*.notification' => 'required|string',
            'settings.*.channel' => 'required|string',
            'settings.*.enabled' => 'required|boolean',
        ]);

        foreach ($settings['settings'] as $s) {
            DB::table('user_notification_prefs')->updateOrInsert(
                ['user_id' => $user->id, 'notification' => $s['notification'], 'channel' => $s['channel']],
                ['enabled' => $s['enabled'], 'updated_at' => now()]
            );
        }

        return response()->json(['success' => true, 'message' => 'All staff notification settings saved.']);
    }

    public function staffPreferences(Request $request): JsonResponse
    {
        $user = $request->user();
        $data = $request->only(['theme', 'language', 'sidebar_collapsed', 'items_per_page', 'date_format']);

        foreach ($data as $key => $value) {
            DB::table('user_preferences')->updateOrInsert(
                ['user_id' => $user->id, 'key' => $key],
                ['value' => $value, 'updated_at' => now()]
            );
        }

        return response()->json(['success' => true, 'message' => 'Preferences saved.']);
    }
}
