@extends('main')

@section('content')
<div class="account__text-header">
    <p>
        <a href="{{route ('home.index') }}">Trang chủ
            <i class="fa-solid fa-angles-right"></i>
        </a> 
        <a href="{{ route('store') }}">Cửa hàng
            <i class="fa-solid fa-angles-right"></i>
        </a>
        Giỏ hàng
    </p>
</div>

<div class="checkout-content-body">
    <div class="container">
        <div class="row">
            <form action="{{ route('cart.checkout') }}" method="POST">
                @csrf
                <div class="col-xs-8 col-sm-12 col-lg-8">
                    <div class="subject">
                        <h2>Thông tin thanh toán</h2>
                        <div>
                            <table>
                                <tr>
                                    <td>
                                        <p>Họ tên</p>
                                        <input type="text" name="name" value="{{ $user->name }}" placeholder="Họ tên" id="">
                                    </td>
                                    <td>
                                        <p>Số điện thoại</p>
                                        <input type="text" name="phone" value="{{ $user->phone }}" placeholder="(+84)12345678">
                                    </td>
                                </tr>
                                <tr>   
                                    <td  colspan = 2>
                                        <p>Địa chỉ</p>
                                        <input type="text" name="address" value="{{ $user->address }}" placeholder="Địa chỉ nhận hàng">
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <p>Ghi chú</p>
                                        <textarea name="note" id="" cols="93" rows="10" placeholder=""></textarea>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-lg-4">
                    <div class="subject table-top">
   
                        <h2>Tổng đơn hàng</h2>
                        <div class=" order-total">
                            <div class="list-product">
                                <p> Sản phẩm</p>
                               @foreach($products as $product)
                               <div>
                                   <div class="name-product"><p>{{ $product->product->name }}</p></div>
                                   <div class="price"><p>
                                       {{ number_format($product->sale_price == 0 ? $product->sell_price  : $product->sale_price) }} đ
                                   </p></div>
                               </div>
                               @endforeach
                            </div>
                            <div class="flex subtotal">
                                <p> Tạm tính</p>
                                <p>{{ number_format($user->cart->total ?? 0)}}</p>
                            </div>
                            <div class="flex shipping">
                                <p>Phí vận chuyển</p>
                                <p>15.000 đ</p>
                            </div>
                        </div>
                        <div class="flex total">
                            <p>Tổng cộng</p>
                            <p>{{ isset($user->cart->total) ? number_format($user->cart->total + 15000) : '0' }} đ</p>
                        </div>
                    </div>
                    <div class="subject table-bottom">
                       <h2>Phương thức thanh toán</h2>
                       <div class="payment">
                           <div>
                               <input type="radio" name="payment" value="1">
                               <label for="paypal">VN Pay</label>
                           </div>
                           <div>
                               <input type="radio" name="payment" value="0" checked>
                               <label for="directCheck">Thanh toán trực tiếp</label>
                           </div>
                       </div>
                   </div>
                   <div class="subject btn-payment">
                       <input type="submit" value="Đặt hàng"></input>
                   </div>
                </div>   
            </form>
        </div>
    </div>
 </div>

 @endsection