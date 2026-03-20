<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HoneypotMiddleware
{
    /**
     * Check honeypot field "website" — must be empty.
     * Bots auto-fill hidden form fields; humans leave them blank.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->filled('website')) {
            // Silent reject — bots don't need to know why
            return response()->json([
                'success' => true,
                'message' => 'Lead submitted.',
            ], 200);
        }

        return $next($request);
    }
}

// ---------------------------------------------------------------
// Аннотация (RU):
// HoneypotMiddleware — защита от ботов на публичных формах.
// Скрытое поле "website" в форме — если заполнено, это бот.
// Возвращаем 200 OK (чтобы бот не знал что был заблокирован).
// Зарегистрировать в bootstrap/app.php или kernel.
// Файл: app/Http/Middleware/HoneypotMiddleware.php
// ---------------------------------------------------------------
