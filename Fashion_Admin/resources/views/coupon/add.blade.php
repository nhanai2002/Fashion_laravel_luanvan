@extends('main')

@section('head')
    <script src="/ckeditor5/ckeditor.js"></script>
@endsection

@section('content')
<h4 class="form__title">Thêm mới mã khuyến mãi</h4>
<form action="" method="post" role="form">
  @csrf
    <div class="box-body">
        <div class="row">
            <div class="col-md-5">
                <div class="form-group">
                    <label>Tên</label>
                    <input type="text" name="name" value="{{old('name')}}" class="form-control" placeholder="Nhập tên coupon" required>    
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Mã coupon</label>
                    <input type="text" name="code" value="{{old('code')}}" class="form-control" placeholder="Nhập mã coupon" required>    
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-5">
                <div class="form-group">
                    <label>Số lượng</label>
                    <input type="number" name="quantity" class="form-control" placeholder="Nhập số lượng" required>    
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Giá trị</label>
                    <input type="number" name="value" class="form-control" placeholder="Giảm thẳng(VND) hoặc Giảm phần trăm(%)">  
                </div>
            </div>
        </div>
        <div class="form-group" >
            <label>Loại coupon</label>
            <select name="type" class="form-select" style="width:40%; margin:20px">
                <option value="0">Giảm thẳng</option>
                <option value="1">Giảm phần trăm</option>
            </select>
            
        </div>
        <div class="form-group">
            <label>Mô tả</label>
            <textarea name="description" id="content" class="form-control"></textarea>
        </div>

        <div class="row">
            <div class="col-md-5">
                <div class="form-group">
                    <label>Thời gian bắt đầu</label>
                    <input type="datetime-local" name="time_start" class="form-control">        
                </div>                
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Thời gian kết thúc</label>
                    <input type="datetime-local" name="time_end" class="form-control">  
                </div>                
            </div>
        </div>
        <div class="form-group">
            <label>Kích hoạt</label>
            <br>
            <span class="radio">
                <label>
                    <input type="radio" name="status" id="active" value="1" checked="">
                    Có
                </label>
            </span>
            <span class="radio">
                <label>
                    <input type="radio" name="status" id="no_active" value="0">
                    Không
                </label>
            </span>
            
        <div class="box-footer">
      <button type="submit" class="btn btn-success">Tạo coupon</button>
    </div>
  </form>
@endsection

@section('footer')
<script>
    ClassicEditor
        .create( document.querySelector( '#content' ) )
        .catch( error => {
            console.error( error );
        } );
</script>
@endsection