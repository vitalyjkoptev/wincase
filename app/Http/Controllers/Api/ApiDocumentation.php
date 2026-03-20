<?php

namespace App\Http\Controllers\Api;

/**
 * @OA\Info(
 *     title="WinCase CRM v4.0 API",
 *     version="4.0.0",
 *     description="Complete REST API for Immigration Bureau CRM. 212+ endpoints across 22 modules.",
 *     @OA\Contact(email="wincasetop@gmail.com"),
 *     @OA\License(name="Proprietary")
 * )
 *
 * @OA\Server(url="https://api.wincase.pro/api/v1", description="Production")
 * @OA\Server(url="http://localhost:8000/api/v1", description="Local Development")
 *
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     description="Sanctum Bearer Token. Obtain via POST /auth/login"
 * )
 */
class ApiDocumentation {}
