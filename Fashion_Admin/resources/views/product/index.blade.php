@extends('main')

@section('content')

<h4 class="form__title">Sản phẩm</h4>
<div class="search__products">
    <form class="col-xs-3 col-sm-3 col-lg-3 search">
        <input type="text" name="search" id="search" placeholder="Tên / mã sản phẩm">
        <button type="submit">
            <i class="fa fa-search" aria-hidden="true" style="top:29px"></i>
        </button>
    </form>
    @if(hasPermission('create_product', $check_permissions))
    <div class="button_add">
        <a href="/admin/product/add" class="btn btn-dark" style="">Thêm mới + </a>
    </div> 
    @endif   
</div>

<table class="table">
    <thead>
        <tr>
            <th>Mã sản phẩm</th>
            <th>Hình ảnh</th>
            <th scope="col">Tên</th>
            <th scope="col">Danh mục</th>
            <th>Hoạt động</th>
            <th style="width: 50px">&nbsp;</th>
        </tr>
    </thead>
    <tbody>
        @foreach($products as $key => $product)
        <tr>
            <td>{{ $product->code }}</td>
            <td>
                @if($product->images->first())
                    <img src="{{ $product->images->first()->url }}" style="height: 70px">
                @else
                    <span style="color: red; font-weight: 500">Chưa có ảnh</span>
                @endif
            </td>
            <td style="width:400px">{{ $product->name }}</td>
            <td>{{ $product->category->name }}</td>
            <td>{!! active($product->status) !!}</td>
            <td class="form__column">
                @if(hasPermission('change_status_product', $check_permissions))
                <a class="btn btn-warning btn-sm" onclick="changeStatus({{ $product->id }}, '/admin/product/active')" title="Đổi trạng thái">
                    <i class="fa-solid fa-bolt"></i>
                </a>
                @endif
                @if(hasPermission('edit_product', $check_permissions))
                <a class="btn btn-primary btn-sm" href="/admin/product/edit/{{ $product->id }}">
                    <i class="fa-solid fa-pen-to-square"></i>
                </a>
                @endif
                @if(hasPermission('delete_product', $check_permissions))
                <a href="#" onclick="removeRow({{ $product->id }}, '/admin/product/destroy')" class="btn btn-danger btn-sm">
                    <i class="fa-solid fa-trash"></i>
                </a>
                @endif
            </td>  
        </tr>
    @endforeach

    </tbody>
</table>

@endsection