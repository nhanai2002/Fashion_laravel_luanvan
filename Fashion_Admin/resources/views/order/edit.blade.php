@extends('main')

@section('content')
<div class="status__header">
    <div id="action">
        @if($order->order_status == 0)
        <div class="arrow {{ $order->order_status == 0 ? 'active-arrow' :'' }}">
            <div class="arrow-body >">
                <span class="active__text" >Đã hủy</span>
            </div>
            <div class="arrow-right">
            </div>
        </div>

        @else
        <div class="arrow {{ $order->order_status == 1 ? 'active-arrow' :'' }}">
            <div class="arrow-body >">
                <span class="active__text" >Chờ xác nhận</span>
            </div>
            <div class="arrow-right">
            </div>
        </div>
        <div class="arrow {{ $order->order_status == 2 ? 'active-arrow' :'' }}">
            <div class="arrow-body">
                <span class="active__text" >Đang xử lý</span>
            </div>
            <div class="arrow-right">
            </div>
        </div>
        <div class="arrow {{ $order->order_status == 3 ? 'active-arrow' :'' }}">
            <div class="arrow-body">
                <span class="active__text" >Đang giao</span>
            </div>
            <div class="arrow-right">
            </div>
        </div>
        <div class="arrow {{ $order->order_status == 4 ? 'active-arrow' :'' }}">
            <div class="arrow-body">
                <span class="active__text {!! $order->order_status == 1 ? 'active-arrow' :'' !!}>" >Hoàn thành</span>
            </div>
            <div class="arrow-right">
            </div>
        </div>

        @endif            
    </div>
    <span>
        <div class="button_add">
            <a href="/admin/order/export-pdf/{{ $order->id }}" class="btn btn-warning">
                Tải xuống
                <i class="fa-solid fa-download"></i>
            </a>
        </div>
        @if($order->order_status != 4)
        <div class="button_add">
            <button class="btn btn-danger change-status" data-status="0" data-order-id ="{{ $order->id }}">
                Hủy đơn
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        @endif
        <div class="button_add">
            @if($order->order_status > 0 && $order->order_status < 4)
            <button class="btn btn-success change-status" data-status="{{ $order->order_status }}" data-order-id ="{{ $order->id }}">
                @if($order->order_status == 1 )
                    Xác nhận
                    <i class="fa-solid fa-check-double"></i>
                @else
                    Bước tiếp theo
                    <i class="fa-solid fa-forward-step"></i>
                @endif
            </button>
            @else
            <button class="btn btn-success">
                {{ getDisplayStatusOrder($order->order_status) }}
            </button>
            @endif
        </div>        
    </span>
</div>


<h4 class="form__title">Chi tiết đơn hàng</h4>
<br>
<div>
    <div class="form-group">
        <label style="margin-right: 10px">Mã đơn hàng: </label>
        <span>{{ $order->code }}</span>       
    </div>
    <div class="form-group">
        <label style="margin-right: 10px">Người đặt hàng: </label>
        <span>{{ $order->user->name }}</span>       
    </div>
    <div class="form-group">
        <label style="margin-right: 10px">Đặt hàng lúc: </label>
        <span>{{ \Carbon\Carbon::parse($order->order_day)->format('H:i d/m/Y') }}</span>       
    </div>

    <div class="box-body">        
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">Hình ảnh</th>
                    <th scope="col">Sản phẩm</th>
                    <th scope="col">Loại</th>
                    <th scope="col">Giá</th>
                    <th scope="col">Số lượng</th>
                    <th scope="col">Tổng</th>
                </tr>
            </thead>

            <tbody >
                @foreach($order->order_items as $item)
                @if($item->warehouse_item)
                <tr>
                    <td>
                        <img src="{{ $item->warehouse_item->product->images->first()->url }}" style="height: 100px">
                    </td>
                    <td style="width: 350px">{{ $item->warehouse_item->product->name}}</td>
                    <td>{{ $item->warehouse_item->color->name .', '. $item->warehouse_item->size->name}}</td>
                    <td>{{ number_format($item->price)  }} đ</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ number_format($item->quantity * $item->price )}} đ</td>
                </tr>
                @endif
                @endforeach
            </tbody>
        </table>

        <div class="bill__total">
            <div class="bill__item">
                <span>Phí vận chuyển:</span>
                <span class="bill__total-price">15.000 đ</span>
            </div>
            <div class="bill__item">
                <span style="padding-right: 45px">Tổng cộng:</span>
                <span class="bill__total-price">{{ number_format($order->total) }}đ</span>
            </div>
        </div>

    </div>
</div>
@endsection

@section('footer')
<script>
    $('.change-status').click(function(e) {
        e.preventDefault();

        var orderId = $(this).data('order-id');
        var status = $(this).data('status');
        $.ajax({
            url: '/admin/order/edit/' + orderId,
            type: 'POST',
            data: { 
                status: status
            },
            success: function(response) {
                if(response.error == false){
                    window.location.reload();
                }
                else{
                    console.error('Đã xảy ra lỗi!');
                }
            },
            error: function(xhr, status, error) {
                console.error('Đã xảy ra lỗi:', error);
            },
        });
    });
</script>
@endsection