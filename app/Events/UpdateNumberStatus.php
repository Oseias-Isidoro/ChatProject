<?php

namespace App\Events;

use App\Models\Number;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UpdateNumberStatus implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Number $number;

    public function __construct(Number $number)
    {
        $this->number = $number;
    }

    public function broadcastOn()
    {
        return new Channel("number.{$this->number->id}");
    }

    public function broadcastWith(): array
    {
        return [];
    }
}
