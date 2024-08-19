@extends('main')

@section('slider')
<div id="myCarousel" class="carousel slide" data-ride="carousel">
    <div class="carousel-inner" role="listbox">
        <div class="item active">
            <img src="/template/asset/image/1.png" alt="" width="100%">
            <div class="carousel-caption">
            </div>      
        </div>
        <div class="item">
            <img src="/template/asset/image/3.png" alt="" width="100%">
            <div class="carousel-caption">
            </div>      
        </div>
        <div class="item">
            <img src="/template/asset/image/2.png" alt="" width="100%">
            <div class="carousel-caption">
            </div>      
        </div>
        <div class="item">
            <img src="/template/asset/image/4.png" alt=""  width="100%">
            <div class="carousel-caption">
            </div>      
        </div>
    </div>
      <!-- Left and right controls -->
    <a class="left carousel-control" href="#myCarousel" role="button" data-slide="prev">
        <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
        <span class="sr-only">Trước</span>
    </a>
    <a class="right carousel-control" href="#myCarousel" role="button" data-slide="next">
        <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
        <span class="sr-only">Sau</span>
    </a>
</div>
<div class="commit">
    <div>
        <i class="fa fa-check" aria-hidden="true"></i>
        <span>Chất lượng tốt</span>
    </div>
    <div>
        <i class="fa fa-truck" aria-hidden="true"></i>
        <span>Miễn phí giao hàng</span>
    </div>
    <div>
        <i class="fa fa-exchange" aria-hidden="true"></i>
        <span>14 Ngày đổi trả</span>
    </div>
    <div>
        <i class="fa fa-volume-control-phone" aria-hidden="true"></i>
        <span>24/7 Hỗ trợ</span>
    </div>
</div>
<div class="advertise">
    <div class="text-md-right box">
        <img src="/template/asset/image/mua-thu.png" alt="">
        <div class="text text-right">
            <p>Giảm giá 20% cho tất cả đơn hàng</p>
            <b>Bộ sưu tập mùa Thu</b><br>
            
        </div>
    </div>
    <div class="text-md-left box">
        <div class="text text-left">
            <p>Giảm giá 20% cho tất cả đơn hàng</p>
            <b>Bộ sưu tập mùa Đông</b><br>
            
        </div>
        <img src="/template/asset/image/mua-dong.png" alt="">
    </div>
</div>

@endsection

@section('content')
<div class="trendy-products">
    <h2>
        <hr  width="100%" size="5px" align="left" color="pink" />
        SẢN PHẨM MỚI
        <hr  width="100%" size="5px" align="left" color="pink" />
    </h2>
    <div class="list-products"  id="product-list">
        @if($products->count() > 0)
            @foreach($products as $product)
            <div class="item-product">
                <img src="{{$product->images->first()->url}}"><br>
                <div>
                    <div class="name-product">
                        <p>{{ $product->name }}</p><br>
                    </div>
                    <div class="price-product"> 
                        {!! price($product->warehouse_items->first()->sell_price, $product->warehouse_items->first()->sale_price) !!}
                    </div>
                    <div class="option ">
                        <div class="view-details">
                            <a  href="/product/detail/{{ $product->id }}-{{ Str::slug($product->name, '-') }}.html" >
                                <i class="fa fa-eye" aria-hidden="true"></i>
                                Xem chi tiết
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        @else
            <h3>Không tìm thấy sản phẩm</h3>
        @endif
    </div>
</div>

@endsection