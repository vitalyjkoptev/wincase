<?php

// =====================================================
// FILE: routes/channels.php (append — Dashboard channel)
// =====================================================

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Dashboard Private Channel
|--------------------------------------------------------------------------
| Only authenticated users can subscribe to dashboard updates.
| Vue.js connects via Echo: Echo.private('dashboard').listen('.dashboard.updated', ...)
*/

Broadcast::channel('dashboard', function ($user) {
    return $user !== null;
});

// ---------------------------------------------------------------
// Аннотация (RU):
// Авторизация Broadcast channel 'dashboard' — private channel.
// Только аутентифицированные пользователи (Sanctum) могут подписаться.
// Vue.js подключается через Laravel Echo + Reverb WebSocket.
// Файл: routes/channels.php (append)
// ---------------------------------------------------------------
