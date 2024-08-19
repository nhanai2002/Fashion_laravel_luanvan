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
                    <input type="text" name="name" value="{{ $coupon->name }}" class="form-control" required>    
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Mã coupon</label>
                    <input type="text" name="code"  value="{{ $coupon->code }}" class="form-control" required>    
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-5">
                <div class="form-group">
                    <label>Số lượng</label>
                    <input type="number" name="quantity" value="{{ $coupon->quantity }}" class="form-control" required>    
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Giá trị</label>
                    <input type="number" name="value" class="form-control" value="{{ $coupon->value }}">  
                </div>
            </div>
        </div>
        <div class="form-group" >
            <label>Loại coupon</label>
            <select name="type" class="form-select" style="width:40%; margin:20px">
                <option value="0" {{ $coupon->type == 0 ? 'selected' : '' }}>Giảm thẳng</option>
                <option value="1" {{ $coupon->type == 1 ? 'selected' : '' }}>Giảm phần trăm</option>
            </select>
            
        </div>
        <div class="form-group">
            <label>Mô tả</label>
            <textarea name="description" id="content" class="form-control">
                {{ $coupon->description }}
            </textarea>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Thời gian bắt đầu</label>
                    <input type="datetime-local" name="time_start"  value="{{ $coupon->time_start }}" class="form-control">        
                </div>                
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Thời gian kết thúc</label>
                    <input type="datetime-local" name="time_end" value="{{ $coupon->time_end }}" class="form-control">  
                </div>                
            </div>
        </div>
        <div class="form-group">
            <label>Kích hoạt</label>
            <br>
            <span class="radio">
                <label>
                    <input type="radio" name="status" id="active" value="1" checked="" {{ $coupon->status == 1 ? 'checked':'' }}>
                    Có
                </label>
            </span>
            <span class="radio">
                <label>
                    <input type="radio" name="status" id="no_active" value="0" {{ $coupon->status == 0 ? 'checked':'' }}>
                    Không
                </label>
            </span>
            
        <div class="box-footer">
      <button type="submit" class="btn btn-success">Cập nhật</button>
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