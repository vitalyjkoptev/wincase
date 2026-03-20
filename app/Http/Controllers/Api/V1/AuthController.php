<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\Auth\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(protected AuthService $auth) {}

    // POST /api/v1/auth/login
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
            'two_factor_code' => 'nullable|string|size:6',
        ]);

        if ($this->auth->isLocked($request->input('email'))) {
            return response()->json(['success' => false, 'message' => 'Account temporarily locked. Try again in 15 minutes.'], 429);
        }

        $result = $this->auth->login(
            $request->input('email'),
            $request->input('password'),
            $request->input('two_factor_code')
        );

        return response()->json(['success' => true, 'data' => $result]);
    }

    // POST /api/v1/auth/register — only creates 'user' (client) accounts
    public function register(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:30',
        ]);

        $result = $this->auth->registerClient(
            $request->input('name'),
            $request->input('email'),
            $request->input('password'),
            $request->input('phone')
        );

        return response()->json(['success' => true, 'data' => $result], 201);
    }

    // POST /api/v1/auth/logout
    public function logout(Request $request): JsonResponse
    {
        $this->auth->logout($request->user());
        return response()->json(['success' => true, 'message' => 'Logged out.']);
    }

    // GET /api/v1/auth/me
    public function me(Request $request): JsonResponse
    {
        $user = $request->user();
        return response()->json(['success' => true, 'data' => [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
            'phone' => $user->phone,
            'department' => $user->department,
            'avatar_url' => $user->avatar_url,
            'two_factor_enabled' => $user->two_factor_enabled,
            'permissions' => $request->user()->currentAccessToken()->abilities ?? [],
        ]]);
    }

    // POST /api/v1/auth/2fa/enable
    public function enable2FA(Request $request): JsonResponse
    {
        $result = $this->auth->enable2FA($request->user());
        return response()->json(['success' => true, 'data' => $result]);
    }

    // POST /api/v1/auth/2fa/confirm
    public function confirm2FA(Request $request): JsonResponse
    {
        $request->validate(['code' => 'required|string|size:6']);
        $ok = $this->auth->confirm2FA($request->user(), $request->input('code'));
        return response()->json(['success' => $ok, 'message' => $ok ? '2FA enabled.' : 'Invalid code.']);
    }

    // POST /api/v1/auth/2fa/disable
    public function disable2FA(Request $request): JsonResponse
    {
        $request->validate(['password' => 'required|string']);
        $ok = $this->auth->disable2FA($request->user(), $request->input('password'));
        return response()->json(['success' => $ok, 'message' => $ok ? '2FA disabled.' : 'Invalid password.']);
    }

    // POST /api/v1/auth/refresh — refresh token (for biometric re-login)
    public function refresh(Request $request): JsonResponse
    {
        $user = $request->user();

        // Delete current token and create a new one
        $abilities = $request->user()->currentAccessToken()->abilities ?? ['*'];
        $request->user()->currentAccessToken()->delete();
        $token = $user->createToken('crm-token', $abilities);

        return response()->json(['success' => true, 'data' => [
            'token' => $token->plainTextToken,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'avatar_url' => $user->avatar_url,
            ],
        ]]);
    }

    // POST /api/v1/auth/password/change — change own password (boss-only)
    public function changePassword(Request $request): JsonResponse
    {
        $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $usersService = app(\App\Services\Auth\UsersService::class);

        try {
            $ok = $usersService->changeOwnPassword(
                $request->user(),
                $request->input('current_password'),
                $request->input('password')
            );
        } catch (\RuntimeException $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 403);
        }

        if (!$ok) {
            return response()->json(['success' => false, 'message' => 'Current password is incorrect.'], 422);
        }

        return response()->json(['success' => true, 'message' => 'Password changed. Please log in again.']);
    }

    // POST /api/v1/auth/password/forgot
    public function forgotPassword(Request $request): JsonResponse
    {
        $request->validate(['email' => 'required|email']);
        $this->auth->sendPasswordResetLink($request->input('email'));
        return response()->json(['success' => true, 'message' => 'If the email exists, a reset link has been sent.']);
    }

    // POST /api/v1/auth/password/reset
    public function resetPassword(Request $request): JsonResponse
    {
        $request->validate([
            'token' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);
        $ok = $this->auth->resetPassword($request->input('token'), $request->input('password'));
        return response()->json(['success' => $ok, 'message' => $ok ? 'Password reset.' : 'Invalid or expired token.']);
    }
}
