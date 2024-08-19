@extends('main')

@section('content')

<h4 class="form__title">Đơn hàng</h4>

<table class="table">
    <thead>
        <tr>
            <th>Mã đơn hàng</th>
            <th scope="col">Giá</th>
            <th scope="col">Ngày đặt hàng</th>
            <th scope="col">Trạng thái đơn hàng</th>
            <th scope="col">Trạng thái thanh toán</th>
            <th style="width: 30px">&nbsp;</th>
        </tr>
    </thead>
    <tbody>
        @foreach($orders as $order)
        <tr>
            <td>{{ $order->code }}</td>
            <td>{{ number_format($order->total) }} đ</td>
            <td>{{ \Carbon\Carbon::parse($order->order_day)->format('H:i d/m/Y') }}</td>
            <td>{{ getDisplayStatusOrder($order->order_status) }}</td>
            <td>{{ getDisplayStatusPayment($order->payment_status) }}</td>
            <td class="form__column">
                @if(hasPermission('edit_order', $check_permissions))
                <a class="btn btn-primary btn-sm" href="/admin/order/edit/{{ $order->id }}">
                    <i class="fa-solid fa-pen-to-square"></i>
                </a>
                @endif
                @if(hasPermission('export_order', $check_permissions))
                <a href="/admin/order/export-pdf/{{ $order->id }}" class="btn btn-warning btn-sm">
                    <i class="fa-solid fa-download"></i>
                </a>
                @endif
            </td>  
        </tr>
    @endforeach

    </tbody>
</table>

@endsection