@extends('main')


@section('content')
<h4 class="form__title">Tài khoản mới</h4>
<form method="post" role="form">
  @csrf
    <div class="box-body">
        <div class="form-group">
            <label>Tài khoản</label>
            <input type="text" name="username" class="form-control" >
        </div>
    <div class="form-group">
        <label>Email</label>
        <input type="email" name="email" class="form-control" >
    </div>
        
      <div class="form-group">
        <label>Họ tên </label>
        <input type="text" name="name" class="form-control">
      </div>
      <div class="form-group">
        <label>Số điện thoại</label>
        <input type="text" name="phone" class="form-control">
      </div>
      <div class="form-group">
        <label>Mật khẩu</label>
        <input type="password" name="password" class="form-control">
      </div>
      <div class="form-group">
        <label>Nhập lại mật khẩu</label>
        <input type="password" name="password_confirmation" class="form-control">
      </div>

    </div>
    
    <div class="box-footer">
      <button type="submit" class="btn btn-success">Thêm mới</button>
    </div>
  </form>
@endsection