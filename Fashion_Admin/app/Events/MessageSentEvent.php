<?php

namespace FashionAdmin\Events;

use Illuminate\Support\Facades\Log;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class MessageSentEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $conversationId;
    public $messageContent;
    public $user;

    public function __construct($conversationId, $messageContent, $user)
    {
        $this->conversationId = $conversationId;
        $this->messageContent = $messageContent;
        $this->user = $user;
    }


    public function broadcastOn()
    {
        return new PrivateChannel('conversation.' . $this->conversationId);
    }

    // public function broadcastWith()
    // {
    //     return [
    //         'conversationId' => $this->conversationId,
    //         'message' => $this->messageContent,
    //         'userId' => $this->userId
    //     ];
    // }

    public function broadcastAs()
    {
        return 'MessageSentEvent'; 
    }


}
