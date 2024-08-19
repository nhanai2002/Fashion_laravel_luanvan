@extends('main')

@section('content')
<div id="content-rev">
    <input type="hidden" id="TotalSold" value="{{ json_encode($totalSolds) }}" />
    <input type="hidden" id="totals" value="{{ json_encode($totals) }}" />
    <div class="rev">
        <div class="rev-body">
            <div class="total-table total-daily-revenue">
                <p>Tổng sản phẩm</p>
                <span>{{ $productsCount }}</span>
            </div>
            <div class="total-table total-weekly-revenue">
                <p>Doanh thu</p>
                <span>{{ number_format($orderTotal) }} VND</span>
            </div>
            <div class="total-table total-monthly-revenue">
                <p>Khách hàng</p>
                <span>{{ $customersCount }}</span>
            </div>
        </div>
        <div class="rev-chart">
            <div class="rev-chart__item">
                <canvas id="myChart"  width="200" height="110" style="width:197px; height:110px;"></canvas>
            </div>
            <div class="rev-top__products">
                <div class="rev-top__user-lable">Khách hàng tiềm năng</div>
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">Tài khoản</th>
                            <th scope="col">Lượt mua</th>
                            <th scope="col">Tổng</th>
                        </tr>
                    </thead>
                    <tbody class="rev-top__products">
                        @foreach($topUsers as $user)
                        <tr>
                            <td class="rev-top__products-body-name">{{ $user->username }}</td>
                            <td>{{ $user->Purchases }}</td>
                            <td>{{ number_format($user->Total) }} đ</td>
                        </tr>
                        @endforeach
                    </tbody>
    
                </table>
            </div>
        </div>

        <div class="rev-chart__top-products">
            <div class="rev-top__products-lable">Top sản phẩm</div>
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">Mã sản phẩm</th>
                        <th scope="col"">Tên sản phẩm</th>
                        <th scope="col">Lượt bán</th>
                        <th scope="col">Doanh thu</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($topProducts as $product)
                    <tr>
                        <td style="width:20%">{{ $product->code }}</td>
                        <td style="width:40%">{{ $product->name }}</td>
                        <td style="width:20%">{{ $product->totalSold }}</td>
                        <td style="width:20%">{{ number_format($product->productRevenue) }} đ</td>
                    </tr>
                    @endforeach
                </tbody>

            </table>

        </div>
    </div>
</div>

@endsection

@section('footer')
<script>
    var ctx = document.getElementById('myChart').getContext('2d');
    var totals = JSON.parse(document.getElementById('totals').value);
    var totalSolds = JSON.parse(document.getElementById('TotalSold').value);
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4', 'Tháng 5', 'Tháng 6', 'Tháng 7', 'Tháng 8', 'Tháng 9', 'Tháng 10', 'Tháng 11', 'Tháng 12'],
            datasets: [{
                label: 'Số tiền bán được',
                data: totals,
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1,
                yAxisID: 'y-axis-1',
            },
            {
                label: 'Số lượng sản phẩm đã bán',
                data:  totalSolds.map(item => item.total),
                type: 'line',
                fill: false,
                borderColor: 'rgb(75, 0, 130)',
                tension: 0.1,
                yAxisID: 'y-axis-2',
            }]
        },
        options: {
            responsive: true,
            scales: {
                yAxes: [{ 
                    id: 'y-axis-1',
                    type: 'linear',
                    display: true,
                    position: 'left',
                    ticks: {
                        beginAtZero: true // Đảm bảo bắt đầu từ giá trị 0
                    }
                },
                { 
                    id: 'y-axis-2',
                    type: 'linear',
                    display: true,
                    position: 'right',
                    grid: {
                        drawOnChartArea: false,
                    },
                }]
            }
            
        }
    });
</script>

@endsection