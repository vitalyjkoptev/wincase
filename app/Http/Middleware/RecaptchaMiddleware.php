<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class RecaptchaMiddleware
{
    protected const VERIFY_URL = 'https://www.google.com/recaptcha/api/siteverify';
    protected const MIN_SCORE = 0.5;

    /**
     * Validate Google reCAPTCHA v3 token.
     * Requires 'recaptcha_token' field in request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->input('recaptcha_token');
        $secret = config('services.recaptcha.secret_key', env('RECAPTCHA_SECRET_KEY'));

        // Skip in local/testing environment
        if (app()->environment('local', 'testing') || empty($secret)) {
            return $next($request);
        }

        // No token provided — reject
        if (empty($token)) {
            return response()->json([
                'success' => false,
                'message' => 'reCAPTCHA verification required.',
            ], 422);
        }

        try {
            $response = Http::asForm()->post(self::VERIFY_URL, [
                'secret' => $secret,
                'response' => $token,
                'remoteip' => $request->ip(),
            ]);

            $data = $response->json();

            if (!($data['success'] ?? false) || ($data['score'] ?? 0) < self::MIN_SCORE) {
                Log::warning('reCAPTCHA failed', [
                    'ip' => $request->ip(),
                    'score' => $data['score'] ?? null,
                    'action' => $data['action'] ?? null,
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'reCAPTCHA verification failed.',
                ], 422);
            }
        } catch (\Exception $e) {
            Log::error('reCAPTCHA service error', ['error' => $e->getMessage()]);

            // Allow through on service error (fail open) — rate limit still protects
            return $next($request);
        }

        return $next($request);
    }
}

// ---------------------------------------------------------------
// Аннотация (RU):
// RecaptchaMiddleware — проверка Google reCAPTCHA v3.
// Минимальный score: 0.5 (0.0 = бот, 1.0 = человек).
// Пропускает в local/testing окружении.
// Fail open — если сервис Google недоступен, пропускаем
// (rate limit и honeypot всё равно защищают).
// RECAPTCHA_SECRET_KEY из .env.
// Файл: app/Http/Middleware/RecaptchaMiddleware.php
// ---------------------------------------------------------------
