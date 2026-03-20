<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class NewsFeedEvent implements ShouldBroadcastNow
{
    public function __construct(public array $payload) {}

    public function broadcastOn(): Channel
    {
        return new Channel('news-feed');
    }

    public function broadcastAs(): string
    {
        return 'feed.update';
    }

    public function broadcastWith(): array
    {
        return $this->payload;
    }
}
