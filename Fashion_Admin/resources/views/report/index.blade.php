@extends('main')

@section('head')
<style>
    .container_report {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 100%;
        width: 100%;
        margin: 50px 0;
    }
    .box {
        background-color: white;
        border: 1px solid #ccc;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        min-width: 40%;
    }

    .form-group {
        margin-bottom: 15px;
    }

    .form-group label {
        display: block;
        margin-bottom: 5px;
        font-weight: bold;
    }

    .form-group input {
        width: 100%;
        padding: 8px;
        border: 1px solid #ccc;
        border-radius: 4px;
    }

</style>
@endsection


@section('content')

<h4 class="form__title">Báo cáo doanh thu</h4>
<div class="container_report">
    <div class="box">
        <form action="" method="GET">
            @csrf
            <div class="form-group">
                <label for="start_time">Thời gian bắt đầu:</label>
                <input type="date" id="start_time" name="start_time">
            </div>
            <div class="form-group">
                <label for="end_time">Thời gian kết thúc:</label>
                <input type="date" id="end_time" name="end_time">
            </div>
            <div class="form-group">
                <label for="product_code">Tên/Mã sản phẩm:</label>
                <input type="text" id="product_code" name="keyword">
            </div>
            <div style="display: flex; align-items: center; justify-content: center;"">
                <button type="submit" class="btn btn-info">Lọc</button>
            </div>
        </form>
    </div>
</div>
@if(hasPermission('export_report', $check_permissions))
<div class="button_add">
    <a href="{{ route('admin.report.export-excel', request()->query()) }}" class="btn btn-warning" style="">Xuất excel
        <i class="fa-solid fa-upload"></i>
    </a>
</div> 
@endif   

<table class="table">
    <thead>
        <tr>
            <th scope="col">Danh mục</th>
            <th scope="col">Mã sản phẩm</th>
            <th scope="col">Tên sản phẩm</th>
            <th scope="col">Lượt bán</th>
            <th scope="col">Doanh thu</th>
        </tr>
    </thead>
    <tbody>
        @foreach($results as $key => $item)
        <tr>
            <td>{{ Str::limit($item->category_name, 20) }}</td>
            <td>{{ $item->product_code }}</td>
            <td>{{ Str::limit($item->product_name, 40) }}</td>
            <td>{{ $item->total_sold }}</td>
            <td>{{ number_format($item->total_revenue) }} đ</td>

        </tr>
    @endforeach
    </tbody>
</table>

@endsection

