@extends('main')


@section('content')
<h4 class="form__title">Cập nhật màu sắc</h4>
<form action="" method="post" role="form">
  @csrf
    <div class="box-body">
      <div class="form-group">
        <label>Màu</label>
        <input type="text" name="name" class="form-control" value="{{ $color->name }}">
      </div>
    </div> 
    <div class="box-footer">
      <button type="submit" class="btn btn-success">Cập nhật</button>
    </div>
  </form>
@endsection