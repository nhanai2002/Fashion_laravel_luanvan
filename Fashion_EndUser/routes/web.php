<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\LoginGoogleController;

Route::get('/', [HomeController::class, 'index'])->name('home.index');

Route::get('product/detail/{id}-{slug}.html',[HomeController::class, 'detail']);
Route::get('/home/detail/updatePriceDetail',[HomeController::class, 'updatePriceDetail']);


Route::get('login',[AuthController::class, 'showLogin'])->name('login');
Route::post('login',[AuthController::class, 'login']);

// Đăng nhập bằng google
Route::controller(LoginGoogleController::class)->group(function(){
    Route::get('auth/google', 'redirectToGoogle')->name('auth.google');
    Route::get('auth/google/callback', 'handleGoogleCallback');
});

Route::get('register',[AuthController::class, 'showRegister']);
Route::post('register',[AuthController::class, 'register']);

Route::get('forgot-password',[AuthController::class, 'showForgotPassword']);
Route::post('forgot-password',[AuthController::class, 'forgotPassword']);
Route::get('/reset-password', [AuthController::class, 'showResetPassword']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);


//trang phụ
Route::get('/another-page/recruitment',function () {
    return view('another-page/recruitment',['title'=>'Tuyển dụng']); 
})->name('recruitment');
Route::get('/another-page/contact',function () {
    return view('another-page/contact',['title'=>'Liên hệ']); 
})->name('contact');
//lọc sản phẩm và hiển thị sp
Route::get('/home/store', [HomeController::class, 'filterProducts']) ->name('store');
//hiển thị sản phẩm trong từng danh mục
Route::get('category/{id}-{slug}.html', [HomeController::class, 'showCategories']);


Route::middleware(['auth'])->group(function(){
    Route::get('logout',[AuthController::class, 'logout'])->name('custom.logout');
    Route::get('home/order-history/{status?}',[HomeController::class, 'orderHistory'])->name('home.order-history');
    Route::post('home/order-history/{status?}', [RatingController::class, 'store'])->name('reviews.store');

    Route::prefix('cart')->group(function(){
        Route::get('index',[CartController::class, 'index']);
        Route::get('create',[CartController::class, 'create'])->name('create-cart');
        Route::post('update',[CartController::class, 'update']);
        Route::delete('delete-cart-item',[CartController::class, 'destroy']);
        Route::post('apply-coupon',[CartController::class, 'applyCoupon']);
        Route::get('show-info',[CartController::class, 'showInfo'])->name('cart.show-info');
        Route::post('checkout',[CartController::class, 'checkout'])->name('cart.checkout');
        Route::get('cancel-order/{order}',[CartController::class, 'canleOrder']);
        Route::get('success-order/{order}',[CartController::class, 'successOrder']);
        Route::get('/payment-callback', [CartController::class, 'paymentCallback'])->name('payment.callback');
    });

    Route::prefix('account')->group(function(){
        Route::get('index',[AccountController::class, 'index']);
        Route::post('update',[AccountController::class, 'update']);
        Route::post('change-avatar', [AccountController::class, 'changeAvatar'])->name('account.change.avatar');    
    });
});