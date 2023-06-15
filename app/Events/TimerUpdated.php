<?php

// В вашем приложении Laravel
namespace App\Events;

use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class TimerUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $timerValue;

    public function __construct($timerValue)
    {
        $this->timerValue = $timerValue;
    }

    public function broadcastOn()
    {
        return ['timer-channel'];
    }

    public function broadcastAs()
    {
        return 'timer-updated';
    }
}
