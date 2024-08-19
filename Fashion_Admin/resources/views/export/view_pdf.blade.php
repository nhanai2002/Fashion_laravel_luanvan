<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>{{ $title }}</title>
    <style>
        @font-face {
            font-family: 'Roboto';
            font-style: normal;
            font-weight: normal;
            src: url('{{ storage_path('fonts/Roboto-Regular.ttf') }}') format('truetype');
        }
        @font-face {
            font-family: 'Roboto';
            font-style: normal;
            font-weight: bold;
            src: url('{{ storage_path('fonts/Roboto-Bold.ttf') }}') format('truetype');
        }
        @font-face {
            font-family: 'Roboto';
            font-style: italic;
            font-weight: normal;
            src: url('{{ storage_path('fonts/Roboto-Italic.ttf') }}') format('truetype');
        }
        @font-face {
            font-family: 'Roboto';
            font-style: italic;
            font-weight: bold;
            src: url('{{ storage_path('fonts/Roboto-BoldItalic.ttf') }}') format('truetype');
        }
        body {
            font-family: 'Roboto', sans-serif;
        }
        .bill__total {
            margin-top: 20px;
            text-align: right;
        }
        table {
            width: 100%;
        }
        th, td {
            border-bottom: 1px solid #e9e9e9; 
            text-align: center;
        }
    </style>
<body>
    <div class="container">
        <h1 style="text-align: center">{{ $title }}</h1>
        <div>
            <div class="form-group">
                <label style="margin-right: 10px">Mã phiếu nhập: </label>
                <span>{{ $main->code }}</span>       
            </div>
            <div class="form-group">
                <label style="margin-right: 10px">Người lập phiếu: </label>
                <span>{{ $main->user->name ?? $main->user->username}}</span>       
            </div>
            <div class="form-group">
                <label style="margin-right: 10px">Ngày lập phiếu: </label>
                <span>{{ \Carbon\Carbon::parse($main->order_day)->format('H:i d/m/Y')  }}</span>       
            </div>
    
            <h3>Chi tiết {{ $title }}</h3>
    
            <div style="margin-top: 10px">        
                <table>
                    <thead style="border-bottom: 1px solid #333">
                        <tr>
                            <th style="width:40%">Sản phẩm</th>
                            <th style="width:15%">Loại</th>
                            <th style="width:15%">Giá</th>
                            <th style="width:15%">Số lượng</th>
                            <th style="width:15%">Tổng</th>
                        </tr>
                    </thead>
    
                    <tbody>
                        {{-- check lúc đầu cho nhanh --}}
                        @if($type == 0)
                            @foreach($details as $detail)
                            @if($detail->warehouse_item)
                            <tr>
                                <td style="word-wrap: break-word; white-space: normal; border-right: 1px solid #e9e9e9">
                                    {{ $detail->warehouse_item->product->name}}
                                </td>
                                <td style="border-right: 1px solid #e9e9e9">
                                    {{ $detail->warehouse_item->color->name .', '. $detail->warehouse_item->size->name}}
                                </td>                            
                                <td style="border-right: 1px solid #e9e9e9">{{ number_format($detail->base_price, 0) }}</td>
                                <td style="border-right: 1px solid #e9e9e9">{{ $detail->quantity }}</td>
                                <td>{{ number_format($detail->quantity * $detail->base_price, 0) }}</td>
                            </tr>
                            @endif
                            @endforeach
                        @else
                            @foreach($details as $detail)
                            @if($detail->warehouse_item)
                            <tr>
                                <td style="word-wrap: break-word; white-space: normal; border-right: 1px solid #e9e9e9">
                                    {{ $detail->warehouse_item->product->name}}
                                </td>
                                <td style="border-right: 1px solid #e9e9e9">
                                    {{ $detail->warehouse_item->color->name .', '. $detail->warehouse_item->size->name}}
                                </td>                            
                                <td style="border-right: 1px solid #e9e9e9">{{ number_format($detail->price, 0) }}</td>
                                <td style="border-right: 1px solid #e9e9e9">{{ $detail->quantity }}</td>
                                <td>{{ number_format($detail->quantity * $detail->price, 0) }}</td>
                            </tr>
                            @endif
                            @endforeach
                        @endif
                    </tbody>
                </table>

                <h4 class="bill__total">
                    Tổng cộng: <span style="color: red""> {{  number_format($main->total, 0) }}đ</span>
                </h4>
            </div>
                    
            <br>
        </div>
        </div>
    <br>


</body>
</html>