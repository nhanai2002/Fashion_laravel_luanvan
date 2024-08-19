@extends('main')

@section('content')
<h4 class="form__title">Danh sách phiếu nhập</h4>

@if(hasPermission('create_goods_receipt', $check_permissions))
<div class="button_add">
    <a href="/admin/warehouse-item/add" class="btn btn-dark" style="">Lập phiếu nhập + </a>
</div>
@endif
<table class="table table-striped">
    <thead>
        <tr>
            <th>Mã phiếu nhập</th>
            <th>Ngày nhập</th>
            <th>Người lập phiếu</th>
            <th>Tổng cộng</th>
            <th style="width: 50px">#</th>
        </tr>
    </thead>
    <tbody>
        @foreach($list as $item)
        <tr>
            <td>{{ $item->code }}</td>
            <td>{{ \Carbon\Carbon::parse($item->input_day)->format('H:i d/m/Y')  }}</td>
            <td>{{ $item->user->name ?? $item->user->username  }}</td>
            <td>{{ number_format($item->total, 0) }} đ</td>
            <td class="form__column">
                @if(hasPermission('detail_goods_receipt', $check_permissions))
                <a class="btn btn-info btn-sm" href="/admin/goods-receipt/detail/{{ $item->id }}">
                    <i class="fa-solid fa-circle-info"></i>
                </a>
                @endif
                @if(hasPermission('export_goods_receipt', $check_permissions))
                <a class="btn btn-warning btn-sm" href="/admin/goods-receipt/export-pdf/{{ $item->id }}">
                    <i class="fa-solid fa-download"></i>
                </a>
                @endif
                @if(hasPermission('delete_goods_receipt', $check_permissions))
                <a href="#" onclick="removeRow({{ $item->id }}, '/admin/goods-receipt/destroy')" class="btn btn-danger btn-sm">
                    <i class="fa-solid fa-trash"></i>
                </a>
                @endif
            </td>  
        </tr>
    @endforeach

    </tbody>
</table>

@endsection