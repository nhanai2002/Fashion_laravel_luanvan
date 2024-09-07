<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SizeController;
use App\Http\Controllers\ColorController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\GoodsReceiptController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\WarehouseItemController;


// login
Route::get('/', [AuthController::class, 'showLogin'])->name('login');
//Route::get('/admin/login',[AuthController::class, 'showLogin'])->name('login');
Route::post('/admin/login',[AuthController::class, 'login']);


Route::middleware(['auth', 'checkRole', 'checkPermission'])->group(function(){
    Route::prefix('admin')->group(function(){
        Route::get('home', [HomeController::class, 'index'])->name('admin');
        Route::get('logout',[AuthController::class, 'logout']);

        Route::prefix('account')->group(function(){
            Route::get('index',[AccountController::class, 'index'])->name('account.index');
            Route::post('update',[AccountController::class, 'update'])->name('account.update');
            Route::post('change-avatar', [AccountController::class, 'changeAvatar'])->name('account.change.avatar');    
        });
    
    
        //  category 
        Route::prefix('category')->group(function (){
            Route::get('index', [CategoryController::class, 'index']);
            Route::get('add', [CategoryController::class, 'create']);
            Route::post('store', [CategoryController::class, 'store']);
            Route::delete('destroy', [CategoryController::class, 'destroy']);
            Route::get('edit/{category}', [CategoryController::class, 'show']);
            Route::post('edit/{category}', [CategoryController::class, 'update']);    
        });

         //product
        Route::prefix('product')->group(function(){
            Route::get('index', [ProductController::class, 'index']);
            Route::post('get-code', [ProductController::class, 'getCodeProduct']);
            Route::get('add', [ProductController::class, 'create']);
            Route::post('add', [ProductController::class, 'store']);
            Route::delete('destroy', [ProductController::class, 'destroy']);
            Route::get('edit/{product}', [ProductController::class, 'show']);
            Route::post('edit/{product}', [ProductController::class, 'update']);    
            Route::post('active', [ProductController::class, 'active']);    
        });

        // warehouse-item
        Route::prefix('warehouse-item')->group(function(){
            Route::get('index', [WarehouseItemController::class, 'index']);
            Route::get('add', [WarehouseItemController::class, 'create']);
            Route::post('add', [WarehouseItemController::class, 'store']);
            Route::delete('destroy', [WarehouseItemController::class, 'destroy']);
            Route::get('edit/{item}', [WarehouseItemController::class, 'show']);
            Route::post('edit/{item}', [WarehouseItemController::class, 'update']);    
            Route::get('export-excel', [WarehouseItemController::class, 'exportExcel']);    
            Route::post('import-excel', [WarehouseItemController::class, 'importExcel']);    
            Route::get('template-import-excel', [WarehouseItemController::class, 'downloadImportTemplate']);    
        });

        //goods-receipt
        Route::prefix('goods-receipt')->group(function(){
            Route::get('index', [GoodsReceiptController::class, 'index']);
            // Route::get('add', [GoodsReceiptController::class, 'create']);
            // Route::post('add', [GoodsReceiptController::class, 'store']);
            Route::delete('destroy', [GoodsReceiptController::class, 'destroy']);
            Route::get('detail/{item}', [GoodsReceiptController::class, 'detail']);
            Route::get('export-pdf/{item}', [GoodsReceiptController::class, 'exportPDF']);
        });


         //coupon
        Route::prefix('coupon')->group(function(){
            Route::get('index', [CouponController::class, 'index']);
            Route::get('add', [CouponController::class, 'create']);
            Route::post('add', [CouponController::class, 'store']);
            Route::delete('destroy', [CouponController::class, 'destroy']);
            Route::get('edit/{coupon}', [CouponController::class, 'show']);
            Route::post('edit/{coupon}', [CouponController::class, 'update']);    
        });

        // color
        Route::prefix('color')->group(function(){
            Route::get('index', [ColorController::class, 'index']);
            Route::get('add', [ColorController::class, 'create']);
            Route::post('add', [ColorController::class, 'store']);
            Route::delete('destroy', [ColorController::class, 'destroy']);
            Route::get('edit/{color}', [ColorController::class, 'show']);
            Route::post('edit/{color}', [ColorController::class, 'update']);    
        });


        // kích thước
        Route::prefix('size')->group(function(){
            Route::get('index', [SizeController::class, 'index']);
            Route::get('add', [SizeController::class, 'create']);
            Route::post('add', [SizeController::class, 'store']);
            Route::delete('destroy', [SizeController::class, 'destroy']);
            Route::get('edit/{size}', [SizeController::class, 'show']);
            Route::post('edit/{size}', [SizeController::class, 'update']);    
        });

        // khách hàng
        Route::prefix('customer')->group(function(){
            Route::get('index', [CustomerController::class, 'index']);
            Route::get('add', [CustomerController::class, 'create']);
            Route::post('add', [CustomerController::class, 'store']);
            Route::delete('destroy', [CustomerController::class, 'destroy']);
            Route::get('edit/{user}', [CustomerController::class, 'show']);
            Route::post('edit/{user}', [CustomerController::class, 'update']);    
            Route::get('show-role/{user}', [CustomerController::class, 'showRole']);    
            Route::post('show-role/{user}', [CustomerController::class, 'setRole']);    
        });

        // đơn hàng
        Route::prefix('order')->group(function(){
            Route::get('index', [OrderController::class, 'index']);
            Route::get('add', [OrderController::class, 'create']);
            Route::post('add', [OrderController::class, 'store']);
            Route::delete('destroy', [OrderController::class, 'destroy']);
            Route::get('edit/{order}', [OrderController::class, 'show']);
            Route::post('edit/{order}', [OrderController::class, 'update']); 
            Route::get('export-pdf/{order}', [OrderController::class, 'exportPDF']);   
        });

        // vai trò
        Route::prefix('role')->group(function(){
            Route::get('index', [RoleController::class, 'index']);
            Route::get('add', [RoleController::class, 'create']);
            Route::post('add', [RoleController::class, 'store']);
            Route::delete('destroy', [RoleController::class, 'destroy']);
            Route::get('edit/{role}', [RoleController::class, 'show']);
            Route::post('edit/{role}', [RoleController::class, 'update']); 
            Route::get('set-permission/{role}', [RoleController::class, 'getPermission']);
            Route::post('set-permission/{role}', [RoleController::class, 'setPermission']);

        });
        
        Route::prefix('report')->group(function(){
            Route::get('index', [ReportController::class, 'index']);
            Route::get('export-excel', [ReportController::class, 'exportExcel'])->name('admin.report.export-excel');    
        });

        Route::prefix('notification')->group(function(){
            Route::get('index', [NotificationController::class, 'index']);
            Route::get('add', [NotificationController::class, 'create']);  
            Route::post('add', [NotificationController::class, 'store']);
            Route::delete('destroy', [NotificationController::class, 'destroy']);
            Route::get('edit/{noti}', [NotificationController::class, 'show']);
            Route::post('edit/{noti}', [NotificationController::class, 'update']); 
            Route::post('send-notification', [NotificationController::class, 'sendNotification']); 
 
        });

        Route::prefix('chat')->group(function(){
            Route::get('view-chat/{user}',[ChatController::class, 'getConversation']);
            Route::post('change-status',[ChatController::class, 'changeStatusTakeOver']);
            Route::post('send-message',[ChatController::class, 'sendMessage']);

        });



    });
});