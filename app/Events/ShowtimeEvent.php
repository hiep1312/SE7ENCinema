<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow; // Use ShouldBroadcastNow instead of ShouldBroadcast
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Broadcast;

class ShowtimeEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $showtime;
    public $event;
    public $message;

    public function __construct($showtime, $event, $message)
    {
        $this->showtime = $showtime;
        $this->event = $event;
        $this->message = $message;
    }

    public function broadcastOn()
    {
        return new Channel('showtime-channel');
    }
}