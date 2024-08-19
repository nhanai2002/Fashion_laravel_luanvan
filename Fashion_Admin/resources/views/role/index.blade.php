@extends('main')

@section('content')

<h4 class="form__title">Vai trò</h4>
@if(hasPermission('create_role', $check_permissions))
<div class="button_add">
    <a href="/admin/role/add" class="btn btn-dark" style="">Thêm mới + </a>
</div>
@endif
<table class="table">
    <thead>
        <tr>
            <th scope="col">Tên vai trò</th>
            <th scope="col">Mô tả</th>
            <th style="width: 50px">&nbsp;</th>
        </tr>
    </thead>
    <tbody>
        @foreach($roles as $role)
        <tr>
            <td>{{ $role->name }}</td>
            <td>{{ Str::limit($role->description, 50) }}</td>
            <td class="form__column">
                @if($role->id != 1 && $role->id != 2 && hasPermission('set_permission', $check_permissions))
                <a class="btn btn-info btn-sm" href="/admin/role/set-permission/{{ $role->id }}">
                    <i class="fa-solid fa-wand-magic-sparkles"></i>
                </a>
                @endif
                @if($role->id != 1 && $role->id != 2 && hasPermission('edit_role', $check_permissions))
                <a class="btn btn-primary btn-sm" href="/admin/role/edit/{{ $role->id }}">
                    <i class="fa-solid fa-pen-to-square"></i>
                </a>
                @endif
                @if($role->id != 1  && $role->id != 2 && hasPermission('delete_role', $check_permissions))
                <a href="#" onclick="removeRow({{ $role->id }}, '/admin/role/destroy')" class="btn btn-danger btn-sm">
                    <i class="fa-solid fa-trash"></i>
                </a>
                @endif
            </td>  
        </tr>
    @endforeach

    </tbody>
</table>

@endsection