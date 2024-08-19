@extends('main')

@section('content')

<h4 class="form__title">Người dùng</h4>

@if(hasPermission('create_users', $check_permissions))
<div class="button_add">
    <a href="/admin/customer/add" class="btn btn-dark" style="">Tạo tài khoản + </a>
</div>
@endif
<div class="search__products">
    <form class="col-xs-3 col-sm-3 col-lg-3 search">
        <input type="text" name="search" id="search" placeholder="Tên / tài khoản">
        <button type="submit">
            <i class="fa fa-search" aria-hidden="true" style="top:29px"></i>
        </button>
    </form>
</div>

<table class="table">
    <thead>
        <tr>
            <th scope="col">Tên</th>
            <th scope="col">Tài khoản</th>
            <th scope="col">Email</th>
            <th scope="col">Số điện thoại</th>
            <th scope="col">Vai trò</th>
            <th style="width: 50px">#</th>
        </tr>
    </thead>
    <tbody>
        @foreach($customers as $customer)
        <tr>
            <td>{{ $customer->name }}</td>
            <td>{{ $customer->username }}</td>
            <td>{{ $customer->email }}</td>
            <td>{{ $customer->phone }}</td>
            <td>{{ $customer->role->name }}</td>
            <td class="form__column">
                @if($customer->username != 'admin' && hasPermission('set_role', $check_permissions))
                <a class="btn btn-warning btn-sm" href="/admin/customer/show-role/{{ $customer->id }}" placeholder="Phân quyền">
                    <i class="fa-solid fa-user-ninja"></i>
                </a>
                @endif
                @if($customer->username != 'admin' && hasPermission('edit_users', $check_permissions))
                <a class="btn btn-primary btn-sm" href="/admin/customer/edit/{{ $customer->id }}">
                    <i class="fa-solid fa-pen-to-square"></i>
                </a>
                @endif
                {{-- <a href="javascript:void(0)" onclick="changeStatus({{ $customer->id }}, '/admin/customer/block')" class="btn btn-warning btn-sm" title="Khóa tài khoản">
                    <i class="fa-solid fa-lock"></i>
                </a> --}}
            </td>  
        </tr>
    @endforeach

    </tbody>
</table>

@endsection