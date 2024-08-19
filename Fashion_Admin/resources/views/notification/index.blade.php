@extends('main')

@section('content')
<h4 class="form__title">Thông báo</h4>
{{-- @if(hasPermission('create_category', $check_permissions)) --}}
<div class="button_add">
    <a href="/admin/notification/add" class="btn btn-dark" style="">Thêm mới + </a>
</div>
{{-- @endif --}}
<table class="table table-striped">
    <thead>
        <tr>
            <th scope="col">Tiêu đề</th>
            <th scope="col">Nội dung</th>
            <th scope="col">Ngày tạo</th>
            <th scope="col">Gửi lúc</th>
            <th style="width: 100px">&nbsp;</th>
        </tr>
    </thead>
    <tbody>
        @foreach($notifications as $notification)
        <tr>
            <td>{{ $notification->title }}</td>
            <td>{!!  Str::limit($notification->message, 100) !!}</td>
            <td>{{ \Carbon\Carbon::parse($notification->created_at)->format('H:i d/m/Y') }}</td>
            <td>{{ $notification && $notification->date_received ? \Carbon\Carbon::parse($notification->date_received)->format('H:i d/m/Y') : 'Chưa gửi'}}</td>
            <td class="form__column">
                <a href="#" onclick="changeStatus({{ $notification->id }}, '/admin/notification/send-notification')" placeholder="Gửi thông báo" class="btn btn-warning btn-sm">
                    <i class="fa-solid fa-paper-plane"></i>
                </a>
                <a class="btn btn-primary btn-sm" href="/admin/notification/edit/{{ $notification->id }}">
                    <i class="fa-solid fa-pen-to-square"></i>
                </a>
                <a href="#" onclick="removeRow({{ $notification->id }}, '/admin/notification/destroy')" class="btn btn-danger btn-sm">
                    <i class="fa-solid fa-trash"></i>
                </a>
            </td>  
        </tr>
    @endforeach

    </tbody>
</table>
@endsection