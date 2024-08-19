@extends('main')

@section('content')
<h4 class="form__title">Danh sách sản phẩm đã nhập kho</h4>
<div class="button_add">
    @if(hasPermission('export_warehouse', $check_permissions) || hasPermission('import_warehouse', $check_permissions))
    <div class="task-button">
        <div class="btn btn-primary">
            Tác vụ
            <i class="fa-solid fa-caret-down"></i>
        </div>
        <div class="dropdown-menu">
            @if(hasPermission('export_warehouse', $check_permissions))
            <a href="/admin/warehouse-item/export-excel" class="btn btn-light" >Export
                <i class="fa-solid fa-upload"></i>
            </a>
            @endif
            @if(hasPermission('import_warehouse', $check_permissions))
            <div> 
                <!-- Mở modal -->
                <button type="button" class="btn btn-light" data-toggle="modal" data-target="#importModal">
                    Import
                    <i class="fa-solid fa-download"></i>
                </button>
            </div>
            @endif
        </div>
    </div>
    @endif
    @if(hasPermission('create_warehouse', $check_permissions))
    <a href="/admin/warehouse-item/add" class="btn btn-dark">Nhập kho + </a>
    @endif
</div>

<div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importModalLabel">Import excel</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ url('/admin/warehouse-item/import-excel') }}" method="POST" enctype="multipart/form-data" id="import-form">
                    @csrf
                    <input type="file" name="file" accept=".xls, .xlsx" id="import-file" hidden>
                    <button type="button" id="choose-file-button" class="btn btn-secondary">Chọn file</button>
                </form>
                <a href="/admin/warehouse-item/template-import-excel">Tải mẫu import</a>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                <button type="submit" class="btn btn-primary" id="submit-file-button">Import</button>
            </div>
        </div>
    </div>
</div>
<table class="table">
    <thead>
        <tr>
            <th style="width:20%">Mã sản phẩm</th>
            <th style="width:15%">Hình ảnh</th>
            <th style="width:50%">Tên sản phẩm</th>
            <th  style="width:15%; padding-left:100px">#</th>
        </tr>
    </thead>
    <tbody>
        @foreach($list as $productId => $warehouseItems)
            @php
                $product = $warehouseItems->first()->product; // Giả sử bạn có quan hệ 'product' trong model WarehouseItem
            @endphp
            <tr onclick="toggleDetails('details-{{ $productId }}')">
                <td>{{ $product->code }}</td>
                <td><img src="{{ $product->images->first()->url }}" style="width:60px; height:60px"></td>
                <td>{{ $product->name }}</td>
                <td  style="width:20%; padding-left:100px"><i class="fa-regular fa-eye"></i></td>
            </tr>
            <tr id="details-{{ $productId }}" style="display: none; width: 50%">
                <td colspan="3">
                    <table class="table" style="width: 75%; margin:auto">
                        <thead>
                            <tr>
                                <th>Màu sắc</th>
                                <th>Size</th>
                                <th>Số lượng</th>
                                <th style="width: 50px">#</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($warehouseItems as $item)
                                <tr>
                                    <td>{{ $item->color->name ?? 'N/A' }}</td>
                                    <td>{{ $item->size->name ?? 'N/A' }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td class="form__column">
                                        @if(hasPermission('edit_product', $check_permissions))
                                        <a class="btn btn-info btn-sm" href="/admin/product/edit/{{ $item->product->id }}">
                                            <i class="fa-solid fa-circle-info"></i>
                                        </a>
                                        @endif
                                        @if(hasPermission('edit_warehouse', $check_permissions))
                                        <a class="btn btn-primary btn-sm" href="/admin/warehouse-item/edit/{{ $item->id }}">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>


@endsection

@section('footer')
<script>
function toggleDetails(id) {
    var element = document.getElementById(id);
    if (element.style.display === "none") {
        element.style.display = "table-row";
    } else {
        element.style.display = "none";
    }
}

document.getElementById('choose-file-button').addEventListener('click', function() {
    document.getElementById('import-file').click();
});

document.getElementById('submit-file-button').addEventListener('click', function() {
    const fileInput = document.getElementById('import-file');
    if (fileInput.files.length > 0) {
        document.getElementById('import-form').submit();
    } else {
        alert('Bạn chưa chọn file.');
    }
});
</script>
@endsection