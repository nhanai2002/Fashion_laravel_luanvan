@extends('main')

@section('content')
<h4 class="form__title">Mã khuyến mãi</h4>
@if(hasPermission('create_coupon', $check_permissions))
<div class="button_add">
    <a href="/admin/coupon/add" class="btn btn-dark" style="">Thêm mới + </a>
</div>
@endif
<table class="table table-striped">
    <thead>
        <tr>
            <th style="width: 50px">ID</th>
            <th scope="col">Tên</th>
            <th scope="col">Mã coupon</th>
            <th scope="col">Số lượng còn lại</th>
            <th scope="col">Giá trị</th>
            <th scope="col">Loại coupon</th>
            <th scope="col">Trạng thái</th>
            <th style="width: 50px">&nbsp;</th>
        </tr>
    </thead>
    <tbody>
        @foreach($coupons as $key => $coupon)
        <tr>
            <td>{{ $coupon->id }}</td>
            <td>{{ $coupon->name }}</td>
            <td>{{ $coupon->code }}</td>
            <td>{{ $coupon->quantity }}</td>
            <td>{{ $coupon->value }}</td>
            <td>{{ $coupon->type == 0 ? 'Giảm thẳng' : 'Giảm phần trăm' }}</td>
            <td>{!! active($coupon->status) !!}</td>
            @php
                $currentDate = now();
                $isExpired = $coupon->time_end && $coupon->time_end < $currentDate;
            @endphp
            <td class="form__column">
                @if(hasPermission('edit_coupon', $check_permissions))
                <a class="btn btn-primary btn-sm {{ $isExpired ? 'disabled' : '' }}"
                    href="{{ $isExpired ? '#' : '/admin/coupon/edit/' . $coupon->id }}"
                    title="{{ $isExpired ? 'Mã giảm giá đã hết hạn' : 'Chỉnh sửa mã giảm giá' }}">
                    <i class="fa-solid fa-pen-to-square"></i>
                </a>
                @endif
                @if(hasPermission('delete_coupon', $check_permissions))
                <a href="#" onclick="removeRow({{ $coupon->id }}, '/admin/coupon/destroy')" class="btn btn-danger btn-sm">
                    <i class="fa-solid fa-trash"></i>
                </a>
                @endif
            </td>  
        </tr>
    @endforeach

    </tbody>
</table>

@endsection