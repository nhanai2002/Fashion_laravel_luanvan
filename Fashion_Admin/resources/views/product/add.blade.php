@extends('main')

@section('head')
    <script src="/ckeditor5/ckeditor.js"></script>
@endsection

@section('content')
<h4 class="form__title">Thêm mới sản phẩm</h4>

<form action="/admin/product/add" method="post" role="form" enctype="multipart/form-data">
  @csrf
    <div class="box-body">

      <div class="row">
        <div class="col-md-5">
            <div class="form-group">
                <label>Mã sản phẩm</label>
                <input type="text" name="code" value="{{ old('code') }}" class="form-control" readonly required>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>Tên sản phẩm</label>
                <input type="text" name="name" value="{{ old('name') }}" class="form-control" placeholder="Tên sản phẩm" required>
            </div>
        </div>
    </div>
    

      
      <div class="form-group">
        <label>Danh mục</label>
        <select name="category_id" class="form-select">
            @foreach($categories as $item)
              <option value="{{$item->id}}">{{$item->name}}</option>
            @endforeach
        </select>
      </div>

      <div class="form-group">
        <label>Mô tả</label>
        <textarea name="description" id="content" class="form-control"></textarea>
      </div>

      <div class="form-group">
          <label>Hình ảnh</label>
          <input type="file" accept="image/*" name="images[]" id="file" onchange="showFileImage(event)" multiple style="display: none;">
          <br>
          <label for="file" class="btn btn-secondary">
              <i class="fa-solid fa-plus"></i>
          </label>
          <br>
          <div id="output" class="output__images"></div>
      </div>




      <div class="box-footer">
        <button type="submit" class="btn btn-success">Thêm sản phẩm</button>
      </div>
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

    function showFileImage(event) {
        var output = document.getElementById('output');
        var files = event.target.files;

        output.innerHTML = ""; // Xóa hình ảnh hiện tại
        for (var i = 0; i < files.length; i++) {
            var img = document.createElement("img");
            img.src = URL.createObjectURL(files[i]);
            img.classList.add("output__image-show");
            img.onload = function () {
                URL.revokeObjectURL(img.src) // giải phóng bộ nhớ
            }
            output.appendChild(img);
        }
    }

    function addNewField() {
        var addFields = document.getElementById('add-fields');
        var newRow = addFields.firstElementChild.cloneNode(true);            
        addFields.appendChild(newRow);
    }

    function removeField() {
        var addFields = document.getElementById('add-fields');
        if (addFields.childElementCount > 1) {
            addFields.lastElementChild.remove();
        }  
    }
    $(document).ready(function() {
    $.ajax({
        url: "/admin/product/get-code",
        type: "POST",
        dataType: "JSON",
        success: function(response) {
            $('input[name="code"]').val(response.code);
        },
        error: function(xhr, status, error) {
            console.error("Lỗi khi tạo mã sản phẩm!");
        }
    });
});
</script>
@endsection