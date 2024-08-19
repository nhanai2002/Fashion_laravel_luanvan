@extends('main')


@section('content')
<h4 class="form__title">Sửa kích thước</h4>
<form action="" method="post" role="form">
  @csrf
    <div class="box-body">
      <div class="form-group">
        <label>Kích thước</label>
        <input type="text" name="name" class="form-control" value="{{ $size->name }}">
      </div>
    </div> 
    <div class="box-footer">
      <button type="submit" class="btn btn-success">Cập nhật</button>
    </div>
  </form>
@endsection