@extends('main')

@section('content')
<div class="button_add">
    <a href="/admin/goods-receipt/export-pdf/{{ $main->id }}" class="btn btn-warning">
        Tải xuống
        <i class="fa-solid fa-download"></i>
    </a>
</div>

<h4 class="form__title">Chi tiết phiếu nhập</h4>
<br>
<div>
    <div class="form-group">
        <label style="margin-right: 10px">Mã phiếu nhập: </label>
        <span>{{ $main->code }}</span>       
    </div>
    <div class="form-group">
        <label style="margin-right: 10px">Người lập phiếu: </label>
        <span>{{ $main->user->name }}</span>       
    </div>
    <div class="form-group">
        <label style="margin-right: 10px">Ngày lập phiếu: </label>
        <span>{{ $main->input_day }}</span>       
    </div>

    <div class="box-body">        
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">Sản phẩm</th>
                    <th scope="col">Màu sắc</th>
                    <th scope="col">Kích thước</th>
                    <th scope="col">Giá</th>
                    <th scope="col">Số lượng</th>
                    <th scope="col">Tổng</th>
                </tr>
            </thead>

            <tbody >
                @foreach($details as $detail)
                @if($detail->warehouse_item)
                <tr>
                    <td>{{ $detail->warehouse_item->product->name}}</td>
                    <td>{{ $detail->warehouse_item->color->name}}</td>
                    <td>{{ $detail->warehouse_item->size->name}}</td>
                    <td>{{ number_format($detail->base_price, 0)  }}</td>
                    <td>{{ $detail->quantity }}</td>
                    <td>{{ number_format($detail->quantity * $detail->base_price, 0)}}</td>
                </tr>
                @endif
                @endforeach
            </tbody>
        </table>

        <h5 class="bill__total">
            Tổng cộng: <span class="bill__total-price"> {{  number_format($main->total, 0) }}đ</span>
        </h5>
    </div>
            
    <br>
  </div>
@endsection

