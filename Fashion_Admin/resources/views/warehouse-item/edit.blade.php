@extends('main')

@section('head')
    <script src="/ckeditor5/ckeditor.js"></script>
@endsection

@section('content')
<h4 class="form__title">Sửa sản phẩm trong kho</h4>

<form action="" method="post" role="form" enctype="multipart/form-data">
  @csrf
    <div class="box-body">
        <div class="form-group">
            <label>Mã sản phẩm</label>
            <input type="text" value="{{ $item->product->code }}" class="form-control" readonly>
        </div>
        <div class="form-group">
            <label>Tên sản phẩm</label>
            <input type="text" value="{{ $item->product->name }}" class="form-control" readonly>
        </div>
        
        <div class="row">
          <div class="col-md-6">
              <label>Màu sắc</label>
              <input type="text" value="{{ $item->color->name }}" class="form-control" readonly>

          </div>
          <div class="col-md-5">
              <label>Kích thước</label>
              <input type="text" value="{{ $item->size->name }}" class="form-control" readonly>
          </div>
        </div>

        <br>
        <div class="row">
            <div class="col-md-5">
                <label>Giá bán</label>
                <input type="text" name="sell_price" value="{{ round($item->sell_price,2) }}" class="form-control" placeholder="Giá bán...">

            </div>
            <div class="col-md-6">
                <label>Giá khuyến mãi</label>
                <input type="text" name="sale_price" value="{{ round($item->sale_price,2) }}" class="form-control" placeholder="Giá khuyến mãi...">
            </div>
        </div>
    </div>

        
    <br>
    <div class="box-footer">
      <button type="submit" class="btn btn-success">Cập nhật</button>
    </div>
  </form>
@endsection

