@extends('main')

@section('head')
    <script src="/ckeditor5/ckeditor.js"></script>
@endsection

@section('content')
<h4 class="form__title">Thông tin sản phẩm</h4>

<form action="" method="post" role="form" enctype="multipart/form-data">
  @csrf
    <div class="box-body">
        <div class="form-group">
            <label>Mã sản phẩm</label>
            <input type="text" name="code" value="{{ $product->code }}" class="form-control" placeholder="Mã sản phẩm..." required readonly>
        </div>
        <div class="form-group">
            <label>Tên sản phẩm</label>
            <input type="text" name="name" value="{{ $product->name }}" class="form-control" placeholder="Tên sản phẩm..." required>
        </div>
        <div class="form-group">
            <label>Danh mục</label>
            <select name="category_id" class="form-select">
                @foreach($categories as $item)
                  <option value="{{$item->id}}" {{ $item->id == $product->category_id ? 'selected' : '' }} >
                    {{$item->name}}
                </option>
                @endforeach
            </select>
        </div>
      {{-- <div class="row">
          <div class="col-md-6">
              <label>Màu sắc</label>
          </div>
          <div class="col-md-6">
              <label>Kích thước</label>
          </div>
      </div>
      <div id="add-fields">
        @foreach($productDetails as $detail)
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                  <select name="color_id[]" class="form-select">
                    @foreach($colors as $color)
                        <option value="{{ $color->id }}" {{ $detail->color_id == $color->id ? 'selected' : '' }}>
                            {{$color->name}}
                        </option>
                    @endforeach
                  </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <select name="size_id[]" class="form-select">
                      @foreach($sizes as $size)
                          <option value="{{ $size->id }}"  {{ $detail->size_id == $size->id ? 'selected' : '' }}>
                              {{$size->name}}
                          </option>
                      @endforeach
                    </select>
                </div>
            </div>
        </div>
        @endforeach
      </div>
      <div style="text-align: center">
        <button type="button" class="btn btn-warning" onclick="addNewField()">
            <i class="fa-solid fa-plus"></i>
        </button>
        <button type="button" class="btn btn-danger" onclick="removeField()">
            <i class="fa-solid fa-minus"></i>
        </button>
      </div> --}}


      <div class="form-group">
          <label>Mô tả</label>
          <textarea name="description"  id="content" class="form-control"> 
              {{ $product->description }}
          </textarea>
      </div>
    </div>

    <div class="form-group">
        <label>Hình ảnh</label>
        <br>
        @if($images->count() > 0)
        @foreach($images->get() as $image)
            <span>
                <img src="{{ $image->url }}" height="60px" alt="Product Image">
            </span>
        @endforeach
        @else
            <h5 style="color: red">Chưa có ảnh</h5>
        @endif
        
        <br>
        <br>
        <input type="file" accept="image/*" name="images[]" id="file" onchange="showFileImage(event)" multiple style="display: none;">
        Thay đổi ảnh mới
        <br>
        <label for="file" class="btn btn-secondary">
            <i class="fa-solid fa-plus"></i>
        </label>
        <div id="output" class="output__images"></div>

    </div>
    
        
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
    });


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


</script>
@endsection