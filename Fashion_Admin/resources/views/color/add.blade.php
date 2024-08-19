@extends('main')


@section('content')
<h4 class="form__title">Thêm màu mới</h4>
<form action="" method="post" role="form">
  @csrf
    <div class="box-body">
      <div class="form-group">
        <label>Màu</label>
        <input type="text" name="name" class="form-control" placeholder="Màu đen...">
      </div>
    </div> 
    <div class="box-footer">
      <button type="submit" class="btn btn-success">Thêm mới</button>
    </div>
  </form>
@endsection