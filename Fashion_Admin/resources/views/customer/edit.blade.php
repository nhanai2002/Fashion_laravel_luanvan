@extends('main')


@section('content')
<h4 class="form__title">Thông tin khách hàng</h4>
<form method="post" role="form">
  @csrf
    <div class="box-body">
        <div class="form-group">
            <label>Tài khoản</label>
            <input type="text" name="username" class="form-control"  value="{{ $user->username }}" readonly>
        </div>
    <div class="form-group">
        <label>Email</label>
        <input type="email" name="email" class="form-control"  value="{{ $user->email }}">
    </div>
        
      <div class="form-group">
        <label>Họ tên </label>
        <input type="text" name="name" class="form-control"  value="{{ $user->name }}">
      </div>
      <div class="form-group">
        <label>Số điện thoại</label>
        <input type="text" name="phone" class="form-control"  value="{{ $user->phone }}">
      </div>
      <div class="form-group">
        <label>Mật khẩu</label>
        <input type="password" name="password" class="form-control">
      </div>
    </div>
    
    <div class="box-footer">
      <button type="submit" class="btn btn-success">Cập nhật</button>
    </div>
  </form>
@endsection