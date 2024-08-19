@extends('main')


@section('content')
<h4 class="form__title">Sửa danh mục</h4>
<form method="post" role="form">
  @csrf
    <div class="box-body">
      <div class="form-group">
        <label>Tên danh mục</label>
        <input type="text" name="name" class="form-control"  value="{{ $category->name }}">
      </div>
      <div class="form-group">
        <label>Danh mục</label>
        <select name="parent_id" class="form-control">
            <option value="0" {{$category->parent_id == 0 ? 'selected' : ''}}>Danh mục cha</option>
              @foreach($list as $item)
              <option value="{{$item->id}}"
               {{ $item->id == $category->parent_id ? 'selected' : ''}}>
               {{ $item->name }}
              </option>
              @endforeach
          </select>
      </div>
    </div>
    
    <div class="box-footer">
      <button type="submit" class="btn btn-success">Cập nhật</button>
    </div>
  </form>
@endsection