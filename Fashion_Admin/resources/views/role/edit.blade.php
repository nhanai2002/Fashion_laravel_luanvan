@extends('main')


@section('content')
<h4 class="form__title">Thông tin vai trò</h4>
<form action="" method="post" role="form">
  @csrf
    <div class="box-body">
      <div class="form-group">
        <label>Tên vai trò</label>
        <input type="text" name="name" class="form-control" value="{{ $role->name }}">
      </div>

      <div class="form-group">
        <label>Mô tả</label>
        <input type="text" name="description" class="form-control" value="{{ $role->description }}">
      </div>
    </div> 
    <div class="box-footer">
      <button type="submit" class="btn btn-success">Cập nhật</button>
    </div>
  </form>
@endsection