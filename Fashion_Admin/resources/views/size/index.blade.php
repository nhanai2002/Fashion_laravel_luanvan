@extends('main')

@section('content')

<h4 class="form__title">Kích cỡ</h4>
@if(hasPermission('create_size', $check_permissions))
<div class="button_add">
    <a href="/admin/size/add" class="btn btn-dark" style="">Thêm mới + </a>
</div>
@endif
<table class="table table-striped">
    <thead>
        <tr>
            <th scope="col">Kích cỡ</th>
            <th style="width: 50px">&nbsp;</th>
        </tr>
    </thead>
    <tbody>
        @foreach($sizes as $size)
        <tr>
            <td>{{ $size->name }}</td>
            <td class="form__column">
                @if(hasPermission('edit_size', $check_permissions))
                <a class="btn btn-primary btn-sm" href="/admin/size/edit/{{ $size->id }}">
                    <i class="fa-solid fa-pen-to-square"></i>
                </a>
                @endif
                @if(hasPermission('delete_size', $check_permissions))
                <a href="#" onclick="removeRow({{ $size->id }}, '/admin/size/destroy')" class="btn btn-danger btn-sm">
                    <i class="fa-solid fa-trash"></i>
                </a>
                @endif
            </td>  
        </tr>
    @endforeach

    </tbody>
</table>

@endsection