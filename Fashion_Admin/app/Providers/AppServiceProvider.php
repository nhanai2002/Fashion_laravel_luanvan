<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Http\View\Composers\ChatComposer;
use App\Http\View\Composers\PermissionComposer;
use App\Http\View\Composers\NotificationComposer;

class AppServiceProvider extends ServiceProvider
{

    public function register(): void
    {
        $this->app->singleton(
            \FashionCore\Interfaces\IUserRepository::class,
            \FashionCore\Repositories\UserRepository::class,
        );
        $this->app->singleton(
            \FashionCore\Interfaces\ICategoryRepository::class,
            \FashionCore\Repositories\CategoryRepository::class,
        );
        $this->app->singleton(
            \FashionCore\Interfaces\IProductRepository::class,
            \FashionCore\Repositories\ProductRepository::class,
        ); 
        $this->app->singleton(
            \FashionCore\Interfaces\IGoodsReceiptRepository::class,
            \FashionCore\Repositories\GoodsReceiptRepository::class,
        ); 
        $this->app->singleton(
            \FashionCore\Interfaces\IGoodsReceiptDetailRepository::class,
            \FashionCore\Repositories\GoodsReceiptDetailRepository::class,
        ); 
        $this->app->singleton(
            \FashionCore\Interfaces\IImageRepository::class,
            \FashionCore\Repositories\ImageRepository::class,
        );
        $this->app->singleton(
            \FashionCore\Interfaces\ICouponRepository::class,
            \FashionCore\Repositories\CouponRepository::class,
        );
        $this->app->singleton(
            \FashionCore\Interfaces\IColorRepository::class,
            \FashionCore\Repositories\ColorRepository::class,
        );
        $this->app->singleton(
            \FashionCore\Interfaces\ISizeRepository::class,
            \FashionCore\Repositories\SizeRepository::class,
        );
        $this->app->singleton(
            \FashionCore\Interfaces\IWarehouseItemRepository::class,
            \FashionCore\Repositories\WarehouseItemRepository::class,
        );
        $this->app->singleton(
            \FashionCore\Interfaces\IOrderRepository::class,
            \FashionCore\Repositories\OrderRepository::class,
        );
        $this->app->singleton(
            \FashionCore\Interfaces\IOrderItemRepository::class,
            \FashionCore\Repositories\OrderItemRepository::class,
        );
        $this->app->singleton(
            \FashionCore\Interfaces\IRoleRepository::class,
            \FashionCore\Repositories\RoleRepository::class,
        );
        $this->app->singleton(
            \FashionCore\Interfaces\IPermissionRepository::class,
            \FashionCore\Repositories\PermissionRepository::class,
        );
        $this->app->singleton(
            \FashionCore\Interfaces\IRolePermissionRepository::class,
            \FashionCore\Repositories\RolePermissionRepository::class,
        );

        $this->app->singleton(
            \FashionCore\Interfaces\INotificationRepository::class,
            \FashionCore\Repositories\NotificationRepository::class,
        );
        $this->app->singleton(
            \FashionCore\Interfaces\INotificationUserRepository::class,
            \FashionCore\Repositories\NotificationUserRepository::class,
        );

        $this->app->singleton(
            \FashionCore\Interfaces\IConversationRepository::class,
            \FashionCore\Repositories\ConversationRepository::class,
        );
        $this->app->singleton(
            \FashionCore\Interfaces\IMessageRepository::class,
            \FashionCore\Repositories\MessageRepository::class,
        );
        $this->app->singleton(
            \FashionCore\Interfaces\IHistoryTakeoverRepository::class,
            \FashionCore\Repositories\HistoryTakeoverRepository::class,
        );
    }


    public function boot(): void
    {
        View::composer('*', PermissionComposer::class);
        View::composer('*', NotificationComposer::class);
        View::composer('*', ChatComposer::class);
    }
}
