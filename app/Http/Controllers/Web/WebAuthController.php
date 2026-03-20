<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class WebAuthController extends Controller
{
    /**
     * GET /login — показать форму логина.
     * Если уже залогинен — редирект на dashboard.
     */
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect('/');
        }
        return view('auth.signin');
    }

    /**
     * POST /login — аутентификация.
     * Принимает FormData (НЕ JSON — Apache/cPanel не парсит JSON body).
     * Возвращает JSON: {success, token, user, redirect}
     */
    public function login(Request $request)
    {
        // --- 1. Валидация ---
        $request->validate([
            'login'    => 'required|string',
            'password' => 'required|string',
        ]);

        // --- 2. Поиск пользователя по email или телефону ---
        $login = trim($request->input('login'));
        $user = str_contains($login, '@')
            ? User::where('email', $login)->first()
            : User::where('phone', $login)
                ->orWhere('phone', preg_replace('/[\s\-\(\)]/', '', $login))
                ->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials',
            ], 401);
        }

        // --- 3. Проверка пароля (Hash::check — bcrypt) ---
        if (!Hash::check($request->input('password'), $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials',
            ], 401);
        }

        // --- 4. Session login (all roles allowed) ---
        Auth::login($user, $request->boolean('remember'));

        // --- 5. Sanctum token для JS API вызовов из Blade ---
        if ($user->role === 'boss') {
            $abilities = ['*'];
        } elseif ($user->role === 'staff') {
            $abilities = [
                'clients.create', 'clients.edit',
                'cases.create', 'cases.edit', 'cases.assign',
                'leads.create', 'leads.edit', 'leads.assign', 'leads.convert',
                'tasks.create', 'tasks.edit', 'tasks.assign',
                'documents.upload', 'documents.delete',
                'pos.approve',
                'accounting.view',
            ];
        } else {
            // role = 'user' (client) — minimal abilities
            $abilities = [
                'client.profile', 'client.cases', 'client.documents',
                'client.messages', 'client.payments',
            ];
        }

        $token = $user->createToken('web-panel', $abilities)->plainTextToken;

        // --- 7. Обновление last_login (НЕ критично — если упадёт, логин всё равно работает) ---
        try {
            $user->forceFill([
                'last_login_at' => now(),
                'last_login_ip' => $request->ip(),
            ])->saveQuietly();
        } catch (\Throwable $e) {
            Log::warning('Failed to update last_login for user ' . $user->id . ': ' . $e->getMessage());
        }

        // --- 8. Ответ ---
        return response()->json([
            'success'  => true,
            'token'    => $token,
            'user'     => [
                'id'         => $user->id,
                'name'       => $user->name,
                'email'      => $user->email,
                'role'       => $user->role,
                'avatar_url' => $user->avatar_url,
            ],
            'redirect' => match ($user->role) {
                'user'  => '/client-dashboard',
                'staff' => '/staff-dashboard',
                default => '/',
            },
        ]);
    }

    /**
     * GET|POST /logout
     */
    public function logout(Request $request)
    {
        // Удалить web-panel токены
        try {
            if ($user = $request->user()) {
                $user->tokens()->where('name', 'web-panel')->delete();
            }
        } catch (\Throwable $e) {
            Log::warning('Failed to delete tokens on logout: ' . $e->getMessage());
        }

        Auth::logout();

        try {
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        } catch (\Throwable $e) {
            // Session might already be invalid
        }

        return redirect('/login');
    }
}
