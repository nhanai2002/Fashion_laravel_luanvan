@extends('main')

@section('content')

<h4 class="form__title">Màu sắc</h4>
@if(hasPermission('create_color', $check_permissions))
<div class="button_add">
    <a href="/admin/color/add" class="btn btn-dark" style="">Thêm mới + </a>
</div>
@endif
<table class="table table-striped">
    <thead>
        <tr>
            <th scope="col">Màu</th>
            <th style="width: 50px">&nbsp;</th>
        </tr>
    </thead>
    <tbody>
        @foreach($colors as $color)
        <tr>
            <td>{{ $color->name }}</td>
            <td class="form__column">
                @if(hasPermission('edit_color', $check_permissions))
                <a class="btn btn-primary btn-sm" href="/admin/color/edit/{{ $color->id }}">
                    <i class="fa-solid fa-pen-to-square"></i>
                </a>
                @endif
                @if(hasPermission('delete_color', $check_permissions))
                <a href="#" onclick="removeRow({{ $color->id }}, '/admin/color/destroy')" class="btn btn-danger btn-sm">
                    <i class="fa-solid fa-trash"></i>
                </a>
                @endif
            </td>  
        </tr>
    @endforeach

    </tbody>
</table>

@endsection