<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use FashionCore\Models\Cart;
use FashionCore\Models\Coupon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;
use FashionCore\Models\CouponHistory;

class ExpireCoupons extends Command
{
    protected $signature = 'coupon:auto';
    protected $description = 'Hủy các coupon đã hết hạn mà ko sử dụng';
    //protected $expression = '* * * * *'; // 1 phút chạy 1 lần

    public function __construct() {
        parent::__construct();
    }

    public function handle()
    {
        $carts = Cart::whereNotNull('coupon_id')->get();
        foreach($carts as $cart){
            $coupon = Coupon::where('id',$cart->coupon_id)->where('time_end', '<', Carbon::now())->first();
            if($coupon){
                $check = CouponHistory::where('coupon_id', $coupon->id)->first();
                if(!isset($check)){
                    $cart->coupon_id = null;
                    $cart->save();
                }    
            }
        }
        $this->info('Expired coupons have been handled.');

    }

}
