<?php

namespace App\Http\Controllers;

use Exception;
use Carbon\Carbon;
use App\Models\User;
use FashionCore\Models\Cart;
use Illuminate\Http\Request;
use FashionCore\Models\Order;
use App\Mail\ConfirmOrderMail;
use FashionCore\Models\CartItem;
use FashionCore\Models\OrderItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use FashionCore\Interfaces\ICartRepository;
use FashionCore\Interfaces\IUserRepository;
use FashionCore\Interfaces\IOrderRepository;
use FashionCore\Interfaces\ICouponRepository;
use FashionCore\Interfaces\IProductRepository;
use FashionCore\Interfaces\ICartItemRepository;
use FashionCore\Interfaces\ICategoryRepository;
use FashionEndUser\Events\OrderNotificationEvent;
use FashionCore\Interfaces\ICouponHistoryRepository;
use FashionCore\Interfaces\IWarehouseItemRepository;

class CartController extends Controller
{
    protected $cartRepo;
    protected $cartItemRepo;
    protected $productRepo;
    protected $categoryRepo;
    protected $userRepo;
    protected $warehouseItemRepo;
    protected $couponRepo;
    protected $couponHistoryRepo;
    protected $orderRepo;

    private $note;    // tạo đỡ hehe
    private $order_id;    
    public function __construct(ICartRepository $cartRepo, ICartItemRepository $cartItemRepo, IProductRepository $productRepo, ICategoryRepository $categoryRepo,
     IUserRepository $userRepo, IWarehouseItemRepository $warehouseItemRepo, ICouponRepository $couponRepo, ICouponHistoryRepository $couponHistoryRepo, IOrderRepository $orderRepo){
        $this->cartRepo = $cartRepo;
        $this->cartItemRepo = $cartItemRepo;
        $this->productRepo = $productRepo;
        $this->categoryRepo = $categoryRepo;
        $this->userRepo = $userRepo;
        $this->warehouseItemRepo = $warehouseItemRepo;
        $this->couponRepo = $couponRepo;
        $this->couponHistoryRepo = $couponHistoryRepo;
        $this->orderRepo = $orderRepo;
    }

    public function index(){
        $user = Auth::user();

        $cart = $this->cartRepo->buildQuery(['user_id' => $user->id])->with('cart_items')->first();
        $cartItems = null;
        if($cart){
            $cartItems = $this->cartItemRepo->buildQuery(['cart_id' => $cart->id])->get();
        }
        if(isset($cart)){
            $this->UpdateCartTotalPrice($cart->id);
            $cart = $this->cartRepo->buildQuery(['user_id' => $user->id])->with('cart_items')->first();
        }
        return view('cart/index',[
            'title' => 'Giỏ hàng',
            'cartitems' => $cartItems,
            'cart' => $cart
        ]);
        
    }


    public function create(Request $request){
        $user = Auth::user();
        
        if($request->id != 0){
            $check = $this->addtocart($user->id, $request->input('id'), $request->input('size_id'), $request->input('color_id'), $request->num_product);
            
            if($check === false){
                return redirect()->back()->with('error', 'Đã xảy ra lỗi');
            }
        }
        return redirect('cart/index');
    }


    public function applyCoupon(Request $request)  : JsonResponse
    {
        $cart = $this->cartRepo->buildQuery(['id'=> $request->cartId])->first();
        $user = User::find(Auth::id());
        $coupon = $request->coupon;
        if($cart){
            if($coupon){
                $coupon = trim($coupon);
            }
            $couponData = $this->couponRepo->buildQuery([
                'code'=> $coupon,
                'status' => 1,
            ])
            ->where('quantity', '>', 0)
            ->where('time_start', '<=', Carbon::now())
            ->where('time_end', '>=', Carbon::now())
            ->first();
            if($couponData != null){
                // thanh toán rồi mới đưa vào lịch sử
                $check = $this->couponHistoryRepo->buildQuery([
                    'coupon_id'=> $couponData->id, 
                    'user_id'=>$user->id
                ])->first();
                if($check){
                    return response()->json([
                        'message' => 'Bạn đã sử dụng coupon này',
                    ]);    
                }
                // if($cart->coupon_id == null){
                //     $cart->coupon_id = $couponData->id;
                //     $cart->save();
                // }
                $cart->coupon_id = $couponData->id;
                $cart->save();

            }
            else{
                return response()->json([
                    'message' => 'Coupon không hợp lệ',
                ]);  
            }
        }
        return response()->json([
            'message' => 'Áp dụng thành công!',
        ]);    
    }

    public function destroy(Request $request) : JsonResponse
    {
        try{
            $result = $this->cartItemRepo->delete($request->id);
            if($result){
                return response()->json([
                    'error' => false
                ]);    
            }
        }
        catch (\Exception $e){
            echo $e->getMessage();
            return response()->json([
                'error' => true
            ]);
        }
    }

    public function update(Request $request) : JsonResponse
    {
        $cartItem = $this->cartItemRepo->buildQuery(['id' => $request->id])->first();
        if($cartItem){
            $product = $this->warehouseItemRepo->buildQuery(['id' => $cartItem->warehouse_item_id])->first();
            $cart = $this->cartRepo->buildQuery(['id' => $cartItem->cart_id])->first();
            $add = filter_var($request->input('add'), FILTER_VALIDATE_BOOLEAN);
            if($product){
                // thêm
                if($add){
                    if($product->quantity > 0){         // check xem còn đủ số lượng ko
                        $cartItem->quantity++;
                        if($cartItem->quantity > $product->quantity ){
                            return response()->json([
                                'error' => true,
                                'message' => 'Sản phẩm không đủ số lượng!'
                            ]);    
                        }
                        $cartItem->save();
                    }
                    else{
                        return response()->json([
                            'error' => true,
                            'message' => 'Sản phẩm không đủ số lượng!'
                        ]);
                    }
                }

                // giảm
                else{
                    // nếu sp trong cart từ 2
                    if($cart->cart_items->count() >= 2){
                        if($cartItem->quantity > 1){
                            $cartItem->quantity--;
                            $cartItem->save();
                        }
                        else{
                            $this->cartItemRepo->delete($cartItem->id);
                        }
                    }
                    else{
                        if($cartItem->quantity > 1){
                            $cartItem->quantity--;
                            $cartItem->save();
                        }
                        else{
                            $user = Auth::user();
                            if($user){
                                $userDb = $this->userRepo->buildQuery(['id' => $user->id])->first();
                                $userDb->cart_id = null;
                                $this->userRepo->update($user->id, $userDb->toArray());
                            }
                            $this->cartItemRepo->delete($cartItem->id);
                            $this->cartRepo->delete($cart->id);
                        }
                    }
                }
            }
            $this->UpdateCartTotalPrice($cart->id);
        }
        return response()->json([
            'error' => false,
        ]);
    }

    private function UpdateCartTotalPrice($cartId) : bool
    {
        try{
            $cart = $this->cartRepo->buildQuery(['id' => $cartId])->with('cart_items')->first();
            if($cart !== null){
                $cart->total = 0;
                if($cart->cart_items->count() > 0){
                    foreach($cart->cart_items as $cartItem){
                        $product = $this->warehouseItemRepo->buildQuery(['id' => $cartItem->warehouse_item_id])->first();
                        // trong quá trình để trong cart mà có ng đã mua sp đó
                        if($cartItem->quantity > $product->quantity){
                            $cartItem->quantity = $product->quantity;
                            $cartItem->save();
                        }


                        $cart->total += $cartItem->quantity * ($product->sale_price == 0 ? $product->sell_price :  $product->sale_price);
                        // coupon
                        $coupon = $this->couponRepo->buildQuery(['id'=>$cart->coupon_id])->first();
                        if($coupon != null){
                            if($coupon->type == 0){
                                if($coupon->value >= $cart->total){
                                    $cart->total = 0;
                                }
                                else{
                                    $cart->total -= $coupon->value;
                                    if($cart->total < 0){
                                        $cart->total = 0;
                                    }
                                }
                            }
                            else if($coupon->type == 1){
                                $cart->total -= ($cart->total * $coupon->value)/100;
                            }
                        }

                    }
                }
            }
            $cart->save();
            return true;    
        }
        catch(\Exception $e){
            Log::info($e->getMessage());
            Log::error($e->getTraceAsString());
            Log::error($e->getLine());
            return false;
        }
    }

    private function addtocart($userId, $productId, $sizeId, $colorId, $quantity = 0) : bool
    { 
        try{
            $user = $this->userRepo->buildQuery(['id' => $userId])->first();
            if($user){  
                $product = $this->warehouseItemRepo->buildQuery([
                    'product_id' => $productId,
                    'size_id' => $sizeId,
                    'color_id' => $colorId
                    ])->where('quantity','>', 0)
                    ->first();
                if($product == null){
                    return false;
                }

                $checkCart = $this->cartRepo
                    ->buildQuery(['user_id' => $user->id])
                    ->with('cart_items')
                    ->first();
                if(empty($checkCart)){
                    $cart = new Cart();
                    $cart->user_id = $user->id;
                    $cart->coupon_id = null;
                    $cart->total = 0;
                    $cart->save();

                    $cartItem = new CartItem();
                    $cartItem->warehouse_item_id = $product->id;
                    $cartItem->cart_id = $cart->id;
                    $cartItem->quantity = $quantity;
                    $cartItem->save();
    
                    //$cart->total = $product->sale_price == 0 ? $product->sell_price :  $product->sale_price;
                    $cart->save();
                }
    
                // đã có cart
                else{
                    // check xem có sp trong cart trùng với sp mới thêm vào
                    if($checkCart->cart_items->contains('warehouse_item_id', $product->id)){
                        foreach($checkCart->cart_items as $item){
                            if($item->warehouse_item_id ==  $product->id){
                                $item->quantity += $quantity;
                            }
                            $item->save();
                        }
                    }
    
                    // nếu không trùng
                    else{
                        $newCartItem = new CartItem();
                        $newCartItem->warehouse_item_id = $product->id;
                        $newCartItem->cart_id = $checkCart->id;
                        $newCartItem->quantity = $quantity;
                        $newCartItem->save();
                    }
                    $this->UpdateCartTotalPrice($checkCart->id);
                }
            }
            return true;    
        }
        catch(\Exception $e){
            Log::info($e->getMessage());
            Log::error($e->getLine());
            return false;
        }
    }

    public function showInfo(){        
        $user = $this->userRepo->buildQuery(['id'=> Auth::id()])->with(['cart.cart_items'])->first();
        if(!isset($user->cart->cart_items)){
            Session::flash('error', 'Không thể thanh toán khi giỏ hàng trống!');
            return redirect()->back();
        }
        $products = [];
        foreach($user->cart->cart_items as $item){
            $products[] =$this->warehouseItemRepo->buildQuery(['id'=> $item->warehouse_item_id ])->first();
        }
        return view('cart/show-info',[
            'title' => 'Thông tin thanh toán',
            'user' => $user,
            'products' => $products
        ]);
    }

    public function checkout(Request $request){
        $this->validate($request,[
            'name' => 'required',
            'phone' => 'required',
            'address' => 'required',
            'payment' => 'required',
        ],[
            'name' => 'Vui lòng nhập tên',
            'phone' => 'Vui lòng nhập số điện thoại',
            'address' => 'Vui lòng nhập địa chỉ',
            'payment' => 'Vui lòng chọn phương thức thanh toán',
        ]);
        try{
            DB::beginTransaction();
            $this->userRepo->update(Auth::id(), [
                'name' => $request->input('name'),
                'phone' => $request->input('phone'),
                'address' => $request->input('address'),
            ]);
            $payment = $request->input('payment');
            $note = $request->input('note');
            $user = $this->userRepo->buildQuery(['id'=>Auth::id()])->with('cart')->first();
            if($payment == 0){
                $result = $this->createOrder(Auth::user(), $note, $payment, null);
                if($result == 0){
                    Session::flash('error', 'Thanh toán thất bại');
                    return redirect()->back();
                }
                else if($result == 2){
                    Session::flash('error', 'Sản phẩm này đã hết hàng!');
                }
            }
            if($payment == 1){
                // $order_id = random_int(1000, 100000);
                // $this->createOrder(Auth::user(), $note, $payment, $order_id);
                // $order = $this->orderRepo->buildQuery(['id'=>$order_id])->first();
                // DB::commit();
                $this->note = $note;
                return $this->checkout_vnpay($user->cart->total);

            }
            DB::commit();
            Session::flash('success', 'Thanh toán thành công!');
            return redirect()->route('home.order-history');
        }
        catch(Exception $e){
            Log::error('Đã xảy ra một exception: ' . $e->getMessage());
            DB::rollBack();
            Session::flash('error', 'Thanh toán thất bại!');
            return redirect()->back();
        }

    }

    private function createOrder($user, $note, $payment_status, $orderId){
        try{
            $userData = $this->userRepo->buildQuery(['id'=>$user->id])->with([
                'cart',
                'cart.cart_items',
                'cart.cart_items.warehouse_item',
            ])->first();
            foreach($userData->cart->cart_items as $item){
                $warehouse_item = $this->warehouseItemRepo->buildQuery(['id' => $item->warehouse_item_id])->where('quantity', '>', 0)->first();
                if($warehouse_item != null){
                    $warehouse_item->quantity -= $item->quantity;
                    $warehouse_item->save();
                }
                else{
                    return 2;
                }
            }
            // $tmpId = random_int(1000, 100000);
            $tmpId = $this->generateOrderCode();
            $order = new Order();
            $order->id = isset($orderId) ? $orderId : $tmpId;
            $order->code = "OD" .  Carbon::now()->format('YmdHis') . $userData->id;
            $order->note = $note;
            $order->order_day = date('Y-m-d H:i:s');
            $order->order_status = 1;
            $order->payment_status = $payment_status;
            $order->total = $userData->cart->total + 15000;
            $order->user_id = $userData->id;
            $order->save();
            foreach($userData->cart->cart_items as $item){
                $orderItem = new OrderItem();
                $orderItem->warehouse_item_id = $item->warehouse_item_id;
                $orderItem->order_id = $order->id;
                $orderItem->quantity = $item->quantity;
                $orderItem->price = $item->warehouse_item->sale_price == 0 ? $item->warehouse_item->sell_price :  $item->warehouse_item->sale_price;
                $orderItem->total = $item->quantity * $orderItem->price;
                $orderItem->save();
            }
    
            $this->cartRepo->delete($userData->cart->id);
            if($userData->cart->coupon_id){
                $this->couponHistoryRepo->add([
                    'user_id' => $userData->id,
                    'coupon_id' => $userData->cart->coupon_id, 
                    'order_id' => $order->id
                ]);
            }
            
            // Gửi mail
            Mail::to($userData->email)->send(new ConfirmOrderMail($order, $userData)); 
            return 1;    
        }
        catch(Exception $e){     
            Log::error('Đã xảy ra một exception: ' . $e->getMessage() . 'Line: ' . $e->getLine());
            return 0;
        }
    }

    public function canleOrder(Order $order){
        try{
            $order = $this->orderRepo->buildQuery(['id' => $order->id])->with([
                'order_items',
                'order_items.warehouse_item'
            ])->first();
            $getHistory = $this->couponHistoryRepo->buildQuery([
                'order_id' => $order->id, 
                'user_id' => $order->user_id
            ])->first();
            if($getHistory){
                $this->couponHistoryRepo->delete($getHistory->id);
            }
            foreach($order->order_items as $item){
                $warehouse = $this->warehouseItemRepo->buildQuery(['id' => $item->warehouse_item_id])->first();
                $warehouse->quantity += $item->quantity;
                $warehouse->save();
            }
            $order->order_status = 0;
            $order->save();
            Session::flash('success', 'Hủy đơn thành công!');
            return redirect()->back();    
        }
        catch(Exception $e){
            Log::info($e->getMessage());
            Session::flash('error', 'Huỷ đơn thất bại!');
            return redirect()->back();    
        }
    }

    public function successOrder(Order $order){
        try{
            DB::beginTransaction();
            $order = $this->orderRepo->buildQuery(['id' => $order->id])->first();
            $order->order_status = 4;
            $order->save();
            $getHistory = $this->couponHistoryRepo->buildQuery(['order_id' => $order->id, 'user_id' => $order->user_id])->first();
            if($getHistory){
                $coupon = $this->couponRepo->buildQuery(['id' => $getHistory->coupon_id])->first();
                $coupon->quantity--;
                $coupon->save();
            }
            event(new OrderNotificationEvent($order->code));
            DB::commit();
            Session::flash('success', 'Giao hàng thành công!');
            return redirect()->back();    
        }
        catch(Exception $e){
            DB::rollBack();
            Log::info($e->getMessage());
            Session::flash('error', 'Đã xảy ra lỗi!');
            return redirect()->back();    
        }
    }

    private function generateOrderCode() {
        $timestamp = bcmul(microtime(true), '10000', 0); 
        $randomNumber = mt_rand(1000, 9999); 
        $orderCode = bcadd($timestamp, $randomNumber); 
        return $orderCode; 
    }    

    private function checkout_vnpay($total){
        $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
        $vnp_Returnurl = "http://127.0.0.1:8001/cart/payment-callback";
        $vnp_TmnCode = "1L0EHOOL";
        $vnp_HashSecret = "CMOBTIBBQLLPQETMDSGYVDQKVBLDXWOF";
        
        $code = $this->generateOrderCode();     // cho nó tạo mã để tránh dữ liệu trùng
        $this->order_id = $code;
        $vnp_TxnRef = random_int(1000, 100000);
        $vnp_OrderInfo = 'Thanh toán đơn hàng: '. $code;
        $vnp_OrderType = 'billpayment';
        $vnp_Amount = $total * 100;
        $vnp_Locale = 'vn';
        $vnp_BankCode = 'NCB';
        $vnp_IpAddr = $_SERVER['REMOTE_ADDR'];
        $inputData = array(
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            // "vnp_ReturnUrl" => $vnp_Returnurl . "?order_id=" . $order_id,
            "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TxnRef" => $vnp_TxnRef,
        );
    
        if (isset($vnp_BankCode) && $vnp_BankCode != "") {
            $inputData['vnp_BankCode'] = $vnp_BankCode;
        }
        
        ksort($inputData);

        $query = http_build_query($inputData);

        // Tạo chuỗi hash để tính toán chữ ký
        $vnpSecureHash = hash_hmac('sha512', $query, $vnp_HashSecret);
    
        // Thêm chữ ký vào URL redirect
        $vnp_Url .= '?' . $query . '&vnp_SecureHash=' . strtoupper($vnpSecureHash);
        return redirect($vnp_Url);
    }

    
    public function paymentCallback(Request $request)
    {
        $vnp_ResponseCode = $request->input('vnp_ResponseCode');

        $isValidSignature = $this->validateVnpaySignature($request);
        try{
            DB::beginTransaction();
            if ($isValidSignature) {
                if ($vnp_ResponseCode == "00") {
                    $this->createOrder(Auth::user(), $this->note, 1, $this->order_id);
                    DB::commit();
                    Session::flash('success', 'Thanh toán thành công.');
                } 
                else {
                    Session::flash('error', 'Thanh toán thất bại. Vui lòng liên hệ quản trị viên.');
                }
            } 
            else{
                    Session::flash('error', 'Chữ ký không hợp lệ. Vui lòng liên hệ quản trị viên.');
            }
            DB::rollBack();
            return redirect()->route('home.order-history');    
        }
        catch(Exception $e){
            DB::rollBack();
            Session::flash('error', 'Thanh toán thất bại. Vui lòng liên hệ quản trị viên.');
            return redirect()->route('home.order-history');    
        }
    }

    private function validateVnpaySignature(Request $request)
    {
        $vnp_SecureHash = $request->input('vnp_SecureHash');
        $vnp_HashSecret = 'CMOBTIBBQLLPQETMDSGYVDQKVBLDXWOF';

        $inputData = $request->except(['vnp_SecureHashType', 'vnp_SecureHash']);
        ksort($inputData);

        $query = http_build_query($inputData);
        $vnp_SecureHashCalculated = hash_hmac('sha512', $query, $vnp_HashSecret);
        return $vnp_SecureHash == $vnp_SecureHashCalculated;
    }


}
