@extends('main')

@section('content')
<h4 class="form__title">Nhập kho</h4>
<form action="" method="post" role="form" enctype="multipart/form-data">
  @csrf
    <div class="form-group">
        <label style="margin-right: 10px">Ngày lập phiếu</label>
        <input type="datetime-local" name="input_day" value="{{ \Carbon\Carbon::now()->format('Y-m-d\TH:i') }}"  required>          
    </div>
    <div class="box-body">        
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">Sản phẩm</th>
                    <th scope="col">Màu sắc</th>
                    <th scope="col">Kích thước</th>
                    <th scope="col">Giá</th>
                    <th scope="col">Số lượng</th>
                </tr>
            </thead>

            <tbody id="add-fields">
                <tr>
                    <td>
                        <select name="product_id[]" class="form-select">
                            @foreach($products as $product)
                                <option value="{{$product->id}}">{{$product->code .' - '. $product->name}}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <select name="color_id[]" class="form-select">
                            @foreach($colors as $color)
                                <option value="{{$color->id}}">{{$color->name}}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <select name="size_id[]" class="form-select">
                            @foreach($sizes as $size)
                                <option value="{{$size->id}}">{{$size->name}}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <input type="text" name="base_price[]" value="{{old('base_price')}}" required class="input__column">          
                    </td>
                    <td>
                        <input type="number" name="quantity[]" value="{{old('quantity')}}" required class="input__column">          
                    </td>
                </tr>
            </tbody>
        </table>
        <div style="text-align: center">
            <button type="button" class="btn btn-warning" onclick="addNewField()">
                <i class="fa-solid fa-plus"></i>
            </button>
            <button type="button" class="btn btn-danger" onclick="removeField()">
                <i class="fa-solid fa-minus"></i>
            </button>
          </div>

    </div>
            
    <br>
    <div class="box-footer">
      <button type="submit" class="btn btn-success">Nhập kho</button>
    </div>
  </form>
@endsection

@section('footer')
<script>
    function addNewField() {
        var addFields = document.getElementById('add-fields');
        var newRow = addFields.firstElementChild.cloneNode(true);
        // xóa dữ liệu cũ            
        var inputs = newRow.querySelectorAll('input');
        inputs.forEach(function(input) {
            input.value = '';
        });

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