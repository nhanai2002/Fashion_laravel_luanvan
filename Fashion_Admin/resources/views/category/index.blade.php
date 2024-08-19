@extends('main')

@section('content')
<h4 class="form__title">Danh mục</h4>
@if(hasPermission('create_category', $check_permissions))
<div class="button_add">
    <a href="/admin/category/add" class="btn btn-dark" style="">Thêm mới + </a>
</div>
@endif
<table class="table table-striped">
    <thead>
        <tr>
            <th style="width: 50px">Mã</th>
            <th scope="col">Tên</th>
            <th scope="col">Cập nhật</th>
            <th style="width: 100px">&nbsp;</th>
        </tr>
    </thead>
    <tbody>
        {!! categoryView($list, $check_permissions) !!}
    </tbody>
</table>
@endsection