<?php

namespace FashionAdmin\Listeners;

use Carbon\Carbon;
use FashionCore\Models\User;
use Illuminate\Support\Facades\Log;
use FashionCore\Models\Notification;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use FashionEndUser\Events\OrderNotificationEvent;

class SaveOrderNotificationListener
{

    public function __construct()
    {
        
    }

    // listener này chỉ lưu lại thôi
    public function handle(OrderNotificationEvent $event): void
    {
        try {
            $notification = Notification::create([
                'title' => 'Đơn hàng giao thành công',
                'message' => 'Đơn hàng: ' . $event->order_code .' đã giao thành công',
                'date_received' => Carbon::now(),
                'type' => 1
            ]);
            $id = User::where('role_id', '!=', 2)->pluck('id')->toArray();;
            $notification->users()->attach($id);
            Log::info('Tạo thông báo thành công');
        } catch (\Exception $e) {
            Log::error('Lỗi khi tạo thông báo: ' . $e->getMessage());
        }
    }
}
