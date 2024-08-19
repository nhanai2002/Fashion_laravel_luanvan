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

<div class="container shopping-cart">
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-lg-8 ">
            <table>
                <tr>
                    <th style="width: 20%">Sản phẩm</th>
                    <th style="width: 15%">Phân loại</th>
                    <th style="width: 15%">Giá</th>
                    <th style="width: 15%">Số lượng</th>
                    {{-- <th style="width: 10%">Màu</th> --}}
                    {{-- <th style="width: 13%">Kích thước</th> --}}
                    <th style="width: 15%">Tổng</th>
                    <th style="width: 10%">Xoá</th>
                </tr>
                @include('alert')
                @if($cartitems !== null)
                @foreach($cartitems as $item)
                <tr>
                    <td class="td-product">
                        <img src="{{ $item->warehouse_item->product->images->first()->url  }}" alt="">
                        <span>{{ $item->warehouse_item->product->name }}</span>
                    </td>
                    <td>{{ $item->warehouse_item->color->name }} ,
                    
                        {{ $item->warehouse_item->size->name }}
                    </td>
                    <td>
                        {{ number_format($item->warehouse_item->sale_price == 0 ? $item->warehouse_item->sell_price  : $item->warehouse_item->sale_price) }}
                    </td>
                    <td>
                        <div class="quantity" style="margin: 30px 0">
                            <div class="input-btn">
                                <button class="btn btn-primary btn-minus" onclick="updateCart({{ $item->id }}, false)">
                                    <i class="fa fa-minus"></i>
                                </button>
                            </div>
                            <input type="text" id="form-display"value="{{ $item->quantity }}" style="width: 40px">
                            <div class="input-btn">
                                <button class="btn btn-primary btn-plus" onclick="updateCart({{ $item->id }}, true)">
                                    <i class="fa fa-plus"></i>
                                </button>
                            </div>
                        </div>
                        <div>Kho: {{ $item->warehouse_item->quantity }} </div>
                    </td>

                    <td>
                        {{ number_format($item->quantity * ($item->warehouse_item->sale_price == 0 ? $item->warehouse_item->sell_price  : $item->warehouse_item->sale_price)) }}
                    </td>
                    <td>
                        <button onclick="removeRow({{ $item->id }}, '/cart/delete-cart-item/')" class="btn btn-sm btn-primary">
                            <i class="fa fa-times"></i>
                        </button>
                    </td>
                </tr>
                @endforeach
                @else
                    <div class="text-center"><h2>Giỏ hàng trống</h2></div>
                @endif
            </table>
        </div>
        <div class="col-xs-12 col-sm-8 col-lg-4 ">
            <div class="coupon">
                <form action="" >
                    @if(isset($cart))
                    <input type="hidden" id="cartId" value="{{ $cart->id }}"/>
                    @endif
                    <input type="text" id="coupon" name="coupon_code" value="" placeholder="Mã Coupon" class="coupon__text" >
                    <input type="submit" name="" onclick="applyCoupon()" value="Áp dụng" class="coupon__text">
                </form>
            </div>
            <div class="cart-summary">
                <div class="cart-header">
                    <p>Tổng giỏ hàng</p>
                </div>
                <div class="cart-body">
                    <div class="element-cart">
                        <p>Tạm tính</p>
                        <p>{{ number_format($cart->total ?? 0)}} đ</p>
                    </div>
                    <div class="element-cart">
                        @if(isset($cart->coupon_id))
                        {!! checkUsedCoupon($cart->coupon_id) !!}
                        @endif
                    </div>
                    <div class="element-cart">
                        <p>Phí vận chuyển</p>
                        <p>15.000 đ</p>
                    </div>
                </div>
                <div class="cart-footer">
                    <div class="element-cart">
                        <h3>Tổng</h3>
                        <h3>{{ isset($cart->total) ? number_format($cart->total + 15000) : '0' }} đ</h3> 
                    </div>
                    <a href="{{ route('cart.show-info') }}" id="">Tiến hành thanh toán</a>
                </div>
                </div>
            </div>
        </div>
</div>
@endsection