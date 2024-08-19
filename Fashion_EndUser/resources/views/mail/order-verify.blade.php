<!DOCTYPE html>
<html>
<head>
    <title>Xác nhận đơn hàng</title>
    <style>
        table tr td{ padding:10px; text-align: center;}
    </style>
</head>
<body>
    <h1>Cảm ơn {{ $user->name }} đã đặt hàng tại AH Fashion!</h1>
    <p>Đơn hàng của bạn đã được đặt thành công. Dưới đây là chi tiết đơn hàng:</p>
    <p>Mã đơn hàng: {{ $order->code }}</p>
    <p>Thời gian đặt hàng:  {{ \Carbon\Carbon::parse($order->order_day)->format('d-m-Y H:i:s') }}</p>
    <b style="color: red">Tổng giá trị đơn hàng: {{ number_format($order->total) }} VND  <p>(Đã bao gồm phí vận chuyển)</p></b>
    <h3>Chi tiết:</h3>
    <table class="order-details" border="1" cellspacing="0" cellpadding="0">
        <thead>
            <tr>
                <th>Tên sản phẩm</th>
                <th>Phân loại</th>
                <th>Giá</th>
                <th>Số lượng</th>
                <th style="color: rgb(247, 98, 123)">Thành tiền</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($order->order_items as $item)
                <tr>
                    <td>{{ $item->warehouse_item->product->name }}</td>
                    <td>
                        {{ $item->warehouse_item->color->name }},
                        {{ $item->warehouse_item->size->name }}
                    </td>
                    <td>{{ number_format($item->price) }} VND</td>
                    <td>{{ $item->quantity }}</td>
                    <td style="color: rgb(247, 98, 123)">{{ number_format($item->total) }} VND</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <br>
    <b>Nếu bạn có bất kỳ câu hỏi nào, vui lòng liên hệ với chúng tôi qua email hoặc số điện thoại dưới đây.</b>
    <p>Chúc bạn một ngày tốt lành!</p>

    <p>&copy; 2024 - AH Fashion. All rights reserved.</p>
    <p>Email: ahfashion@gmail.com | Số điện thoại: 0909-123-123</p>
</body>
</html>
