<?php

namespace FashionEndUser\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderNotificationEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $order_code;
    public $notification;

    public function __construct($order_code)
    {
        $this->order_code = $order_code;
        $this->notification = [
            'title' => 'Đơn hàng giao thành công!',
            'message' => 'Đơn hàng ' . $order_code . ' đã giao thành công!',
        ];
    }

    public function broadcastOn()
    {
        return new PrivateChannel('admin-channel');
    }

    public function broadcastAs()
    {
        return 'OrderNotificationEvent'; 
    }

}
