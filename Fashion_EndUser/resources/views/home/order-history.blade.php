@extends('main')

@section('content')
<div class="account__text-header">
    <p>
        <a href="{{ route('home.index') }}">Trang chủ
        <i class="fa-solid fa-angles-right"></i>
        </a> 
        Thông Tin Đơn Hàng
    </p>
</div>

<div class="container account-customer">
    <div class="row">       
        <div class="col-xs-12 col-sm-12 col-lg-12 account-customer-order">
            <div class="order-detail">
                <div class="col-xs-12 col-sm-12 col-lg-12 account-customer-order">
                    <div class="action-order">
                        <div><a href="{{ route('home.order-history', ['status' => 0]) }}" class="{{ $active == 0 ? 'active' : '' }}">Tất cả</a></div>
                        <div><a href="{{ route('home.order-history', ['status' => 1]) }}" class="{{ $active == 1 ? 'active' : '' }}">Đã huỷ</a></div>
                        <div><a href="{{ route('home.order-history', ['status' => 2]) }}" class="{{ $active == 2 ? 'active' : '' }}">Chờ xác nhận</a></div>
                        <div><a href="{{ route('home.order-history', ['status' => 3]) }}" class="{{ $active == 3 ? 'active' : '' }}">Đang xử lý</a></div>
                        <div><a href="{{ route('home.order-history', ['status' => 4]) }}" class="{{ $active == 4 ? 'active' : '' }}">Đang giao</a></div>
                        <div><a href="{{ route('home.order-history', ['status' => 5]) }}" class="{{ $active == 5 ? 'active' : '' }}">Hoàn thành</a></div>
                    </div>

                    @if(isset($orders))
                        @foreach($orders as $order)
                            <div class="order-content">
                                <a href="customer-Order-Detail.html">
                                    @foreach($order->order_items as $item)
                                    <div class="product-in-cart">
                                        <div class="order-content-img">
                                            <img src="{{ $item->warehouse_item->product->images->first()->url }}" alt="">
                                        </div>
                                        <div class="order-content-detail">
                                            <div class="name"><span>{{ $item->warehouse_item->product->name }}</span></div>
                                            <div class="type"><span>Phân loại: {{ $item->warehouse_item->color->name .', ' . $item->warehouse_item->size->name }}</span></div>
                                            <div class="quantity"><span>x{{ $item->quantity }}</span></div>
                                        </div>
                                        <div class="order-content-price">
                                            <span>{{ number_format($item->price) }} đ</span>
                                        </div>
                                    </div>
                                    @endforeach

                                    <div class="order-content-total">
                                        <div class="finish-day">
                                            <span>Đặt hàng lúc: {{ \Carbon\Carbon::parse($order->order_day)->format('H:i d/m/Y') }}</span>
                                        </div>
                                        <span>Thành tiền: <p>{{ number_format($order->total) }} đ</p></span>
                                    </div>

                                    @if($order->order_status == 1)
                                        <div class="text-right">
                                            <a class="btn btn-warning" href="/cart/cancel-order/{{ $order->id }}">
                                                Hủy đơn
                                            </a>
                                        </div>
                                    @endif
                                    @if($order->order_status == 3)
                                        <div class="text-right">
                                            <a class="btn btn-info" href="/cart/success-order/{{ $order->id }}">
                                                Đã nhận hàng
                                            </a>
                                        </div>
                                    @endif
                                </a>
                                @if ($order->order_status == 4)
                                @if (!$order->isReviewed)
                                    <div class="text-right">
                                        <button class="btn btn-warning" type="button" data-toggle="collapse" data-target="#review-form-{{ $order->id }}">
                                            Đánh giá sản phẩm
                                        </button>
                                    </div>
                                    <div id="review-form-{{ $order->id }}" class="collapse">
                                        <form class="review-form" action="{{ route('reviews.store') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="order_id" value="{{ $order->id }}">
                        
                                            @foreach($order->order_items as $item)
                                                <input type="hidden" name="product_id[]" value="{{ $item->warehouse_item->product->id }}">
                                                
                                                <div class="form-group">
                                                    <label>Đánh giá cho {{ $item->warehouse_item->product->name }}:</label>
                                                    <div class="rating" id="rating-{{ $item->id }}">
                                                        @for($count = 1; $count <= 5; $count++)
                                                            <span class="star" data-value="{{ $count }}">&#9733;</span>
                                                        @endfor
                                                    </div>
                                                    <input type="hidden" name="rating[{{ $item->warehouse_item->product->id }}]" id="rating-value-{{ $item->id }}" value="{{ old('rating.' . $item->warehouse_item->product->id) }}" />
                                                    {{-- hiển thị lỗi nếu không chọn  sao --}}
                                                    @if ($errors->has('rating.' . $item->warehouse_item->product->id))
                                                        <small class="form-text text-danger">{{ $errors->first('rating.' . $item->warehouse_item->product->id) }}</small>
                                                    @endif
                                                </div>
                                                
                                                <div class="form-group">
                                                    <label for="comment-{{ $item->warehouse_item->product->id }}">
                                                        Bình luận:
                                                    </label><br>
                                                    {{-- hiển thị số ký tự đã nhập --}}
                                                    <small id="char-count-{{ $item->warehouse_item->product->id }}" class="form-text text-muted" style="float: right">( 0/250 )</small>
                                                    <textarea
                                                        maxlength="250" 
                                                        name="comment[{{ $item->warehouse_item->product->id }}]" 
                                                        id="comment-{{ $item->warehouse_item->product->id }}" 
                                                        class="form-control" 
                                                        rows="3"
                                                        {{-- bắt sự kiện nhập và đếm số ký tự --}}
                                                        oninput="updateCharCount({{ $item->warehouse_item->product->id }})"
                                                        cols="50">{{ (old('comment.'. $item->warehouse_item->product->id)) }}</textarea>
                                                </div>
                                            @endforeach
                                            
                                            <button type="submit" class="btn btn-primary">Gửi đánh giá</button>
                                        </form>
                                    </div>
                                @else
                                    <div class="text-right">
                                        <p>Cảm ơn bạn đã đánh giá sản phẩm!</p>
                                    </div>
                                @endif
                            @endif
                            </div>
                        @endforeach
                    @else
                        <h2>Lịch sử trống</h2>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
