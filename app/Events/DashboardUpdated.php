<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DashboardUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * KPI data to broadcast.
     */
    public function __construct(
        public readonly string $section,
        public readonly array $data,
    ) {}

    /**
     * Broadcast on private channel (auth required).
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('dashboard'),
        ];
    }

    /**
     * Event name for Vue.js listener.
     */
    public function broadcastAs(): string
    {
        return 'dashboard.updated';
    }

    /**
     * Data payload.
     */
    public function broadcastWith(): array
    {
        return [
            'section' => $this->section,
            'data' => $this->data,
            'timestamp' => now()->toIso8601String(),
        ];
    }
}

// ---------------------------------------------------------------
// Аннотация (RU):
// DashboardUpdated — Reverb WebSocket event для real-time обновлений.
// Broadcast на private channel 'dashboard' (auth:sanctum).
// broadcastAs() = 'dashboard.updated' — Vue.js слушает через Echo.
// Payload: section (kpi|leads|finance|...) + data + timestamp.
// Вызывается при: новый лид, оплата POS, новый пост, sync завершён.
// Файл: app/Events/DashboardUpdated.php
// ---------------------------------------------------------------
