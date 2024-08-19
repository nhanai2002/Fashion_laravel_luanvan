@extends('main')


@section('content')
<h4 class="form__title">Thêm kích thước mới</h4>
<form action="" method="post" role="form">
  @csrf
    <div class="box-body">
      <div class="form-group">
        <label>Kích thước</label>
        <input type="text" name="name" class="form-control" placeholder="Nhập kích thước...">
      </div>
    </div> 
    <div class="box-footer">
      <button type="submit" class="btn btn-success">Thêm mới</button>
    </div>
  </form>
@endsection