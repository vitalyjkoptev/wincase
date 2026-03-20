<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CorsDomainsMiddleware
{
    /**
     * Restrict public lead form submissions to 4 WinCase domains only.
     */
    protected array $allowedOrigins = [
        'https://wincase.pro',
        'https://www.wincase.pro',
        'https://wincase-legalization.com',
        'https://www.wincase-legalization.com',
        'https://wincase-job.com',
        'https://www.wincase-job.com',
        'https://wincase.org',
        'https://www.wincase.org',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $origin = $request->header('Origin');

        // Skip in local/testing
        if (app()->environment('local', 'testing')) {
            return $next($request);
        }

        // No origin header (direct API call, Postman, n8n) — allow
        if (empty($origin)) {
            return $next($request);
        }

        // Check allowed origins
        if (!in_array($origin, $this->allowedOrigins, true)) {
            return response()->json([
                'success' => false,
                'message' => 'Origin not allowed.',
            ], 403);
        }

        $response = $next($request);

        $response->headers->set('Access-Control-Allow-Origin', $origin);
        $response->headers->set('Access-Control-Allow-Methods', 'POST, OPTIONS');
        $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Accept');

        return $response;
    }
}

// ---------------------------------------------------------------
// Аннотация (RU):
// CorsDomainsMiddleware — ограничение public endpoint 4 доменами WinCase.
// Разрешённые origins: wincase.pro, wincase-legalization.com,
// wincase-job.com, wincase.org (+ www. варианты).
// Пропускает запросы без Origin header (Postman, n8n webhooks, cURL).
// Пропускает в local/testing окружении.
// Файл: app/Http/Middleware/CorsDomainsMiddleware.php
// ---------------------------------------------------------------
