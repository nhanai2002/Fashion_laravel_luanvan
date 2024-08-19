@extends('main')

@section('head')
    <script src="/ckeditor5/ckeditor.js"></script>
@endsection

@section('content')

<h4 class="form__title">{{ $title }}</h4>

<form action="/admin/order/add" method="post" role="form">
    @csrf
    <div class="box-body">
        <div class="row">
        <div class="col-md-6">
            <div class="form-group">
              <label>Nguời dùng</label>
              <select name="user_id" class="form-select ">
                @foreach($customers as $customer)
                  <option value="{{$customer->id}}">{{$customer->username .' - '.$customer->name}}</option>
                @endforeach
            </select>
    
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>Trạng thái đơn hàng</label>
                <select name="order_status" class="form-select">
                    <option value="0">Chờ xác nhận</option>
                    <option value="1">Đang xử lý</option>
                    <option value="2">Đang giao</option>
                    <option value="3">Hoàn thành</option>
                    <option value="4">Đã hủy</option>
                </select>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>Trạng thái thanh toán</label>
                <select name="order_status" class="form-select">
                    <option value="0">Thanh toán trực tiếp</option>
                    <option value="1">Đã thanh toán</option>
                </select>
            </div>
        </div>

        

        </div>
        <div class="form-group">
            <label>Mô tả</label>
            <textarea name="note" class="form-control"></textarea>
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
                            <select name="product_id[]" class="js-example-basic-single form-select ">
                                @foreach($products as $product)
                                    <option value="{{$product->id}}" style="width: 200px">
                                        {{$product->code .' - '. $product->name}}
                                    </option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <select name="color_id[]" class="form-select" style="width:100px">
                                @foreach($colors as $color)
                                    <option value="{{$color->id}}">{{$color->name}}</option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <select name="size_id[]" class="form-select" style="width:100px">
                                @foreach($sizes as $size)
                                    <option value="{{$size->id}}">{{$size->name}}</option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <input type="text" name="base_price[]" value="{{old('base_price')}}" required class="input__column" style="width:150px">          
                        </td>
                        <td>
                            <input type="number" name="quantity[]" value="{{old('quantity')}}" required class="input__column" style="width:150px">          
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
    




        <div class="box-footer">
            <button type="submit" class="btn btn-success">Tạo đơn hàng</button>
        </div>
    </div>
  </form>
@endsection

@section('footer')
<script>
    $(document).ready(function() {
    $('.js-example-basic-single').select2();
    });
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