@extends('main')


@section('content')
<h4 class="form__title">Thêm danh mục</h4>
<form action="/admin/category/store" method="post" role="form">
  @csrf
    <div class="box-body">
      <div class="form-group">
        <label>Tên danh mục</label>
        <input type="text" name="name" class="form-control" placeholder="Nhập tên danh mục">
      </div>
      <div class="form-group">
        <label>Danh mục</label>
        <select name="parent_id" class="form-control">
          <option value="0">Danh mục cha</option>
            @foreach($list as $item)
              <option value="{{$item->id}}">{{$item->name}}</option>
            @endforeach
        </select>
      </div>
    </div>
    
    <div class="box-footer">
      <button type="submit" class="btn btn-success">Tạo danh mục</button>
    </div>
  </form>
@endsection