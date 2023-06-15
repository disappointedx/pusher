<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UpdateScoreEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    private $game_user;
    /**
     * Create a new event instance.
     */
    public function __construct($game_user)
    {
        $this->game_user = $game_user;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn() : array
    {
        return ['update_score'];
    }

    public function broadcastAs()
    {
        return 'update_score';
    }
    public function broadcastWith(): array
    {
        return [
            'game_user' => $this->game_user,
        ];
    }
}
