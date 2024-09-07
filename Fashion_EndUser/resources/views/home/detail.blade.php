@extends('main')

@section('content')
<div class="account__text-header">
    <p>
        <a href="{{ Route('home.index') }}">Trang chủ
            <i class="fa-solid fa-angles-right"></i>
        </a> 
        <a href="{{ Route('store') }}">Sản phẩm
            <i class="fa-solid fa-angles-right"></i>
        </a> 
        Chi tiết sản phẩm
    </p>
</div>

<div class="shopDetails">
    <div class="slider file-img">
        @foreach($product->images as $key => $image)
        <div class="mySlides" style="{{ $key == 0 ? 'display: block;' : 'display: none;' }}">
            <img src="{{ $image->url }}">
        </div>
        @endforeach

        <a class="prev" onclick="plusSlides(-1)"><i class="fa fa-angle-left" aria-hidden="true"></i></a>
        <a class="next" onclick="plusSlides(1)"><i class="fa fa-angle-right" aria-hidden="true"></i></a>
        <div class="row">
            @foreach($product->images as $key => $image)
            <div class="column">
                <img class="demo cursor" src="{{ $image->url }}" onclick="currentSlide({{ $key + 1 }})" alt="">
            </div>
            @endforeach
        </div>
    </div>



    <div class="describe">
        <div class="detail name-product ">
            <p>{{ $product->name }}</p>
        </div>
        <div class="detail evaluate ">
            <div class="evaluate-start">
                <div class="rating-display" id="rating-display-{{ $product->id }}">
                    @for($count = 1; $count <= 5; $count++)
                        <span class="star" data-value="{{ $count }}" style="color: {{ $count <= $averageRating ? '#ff97a0' : '#ccc' }}">&#9733;</span>
                    @endfor
                </div>
            </div>
            <small class="pt-1">({{ $totalRatings }} đánh giá về sản phẩm)</small>
        </div>



      {{-- sẽ thay đổi --}}
        <div class="detail price" id="price-display" style="display:flex; flex-direction: column">
            {{-- giá --}}
        </div>


        @if($warehouse_items->min('sell_price')!= 0)
        <form action="{{ route('create-cart') }}" method="GET">
            @csrf
            <input type="hidden" name="id" value="{{ $product->id }}" id="product_id">
        
            <div class="detail rd size">
                <label for="size">Kích cỡ:</label>
                @foreach ($sizes as $item)
                @php
                    $hasStock = false;
                    foreach ($warehouse_items as $warehouse_item) {
                        if ( $item->id == $warehouse_item -> size_id) {
                            $hasStock = true;
                            break;
                        }
                    }
                @endphp
                @if ($hasStock)
                    <div class="size-option">
                        <input checked type="radio" class="custom-control-input size-radio" id="size-{{ $item->id }}" name="size_id" value="{{ $item->id }}">
<label for="size-{{ $item->id }}">{{ $item->name }}</label>
                    </div>
                @else
                    <div class="size-option" style="display: none">
                        <input disabled type="radio" class="custom-control-input size-radio" id="size-{{ $item->id }}" name="size_id" value="{{ $item->id }}">
                        <label for="size-{{ $item->id }}">{{ $item->name }}</label>
                    </div>
                @endif
                @endforeach


            </div>
            <div class="detail rd color">
                <label for="color">Màu:</label>

                @foreach ($colors as $item)
                @php
                    $hasStock = false;
                    foreach ($warehouse_items as $warehouse_item) {
                        foreach ($sizes as $item_size){
                            if ( $item->id == $warehouse_item->color_id && $item_size->id == $warehouse_item -> size_id ) {
                                $hasStock = true;
                                break;
                            }
                        }
                    }
                @endphp
                
                @if ($hasStock)
                    <div class="color-option">
                        <input checked type="radio" class="custom-control-input color-radio" id="color-{{ $item->id }}" name="color_id" value="{{ $item->id }}">
                        <label for="color-{{ $item->id }}">{{ $item->name }}</label>
                    </div>
                @else
                    <div class="color-option" style="display: none">
                        <input disabled type="radio" class="custom-control-input color-radio" id="color-{{ $item->id }}" name="color_id" value="{{ $item->id }}">
                        <label  for="color-{{ $item->id }}">{{ $item->name }}</label>
                    </div>
                @endif
                
                @endforeach

            </div>

        
            <div class="action">
                <div class="quantity">
                    <div class="input-btn">
                        <button type="button" class="btn btn-primary btn-minus" onclick="delItem()">
                            <i class="fa fa-minus"></i>
                        </button>
                    </div>
                    <input type="text" id="form-display"  name="num_product" value="1">
                    <div class="input-btn">
                        <button type="button" class="btn btn-primary btn-plus" onclick="addItem()">
                            <i class="fa fa-plus"></i>
                        </button>
                    </div>
                </div>
                <div class="btn-add">
                    <button class="btn btn-primary" type="submit">
                        <i class="fa fa-shopping-cart mr-1"></i>
                        Thêm vào giỏ hàng
                    </button>
                </div>
            </div>
</form>

        @endif
        
    </div>
</div>





<div class="product-info">
    <nav>
        <div class="subject active" id="sub-info" onclick="info()">
            <h4> Thông tin sản phẩm</h4>
        </div>
        <div class="subject" id="sub-review" onclick="review()">
            <h4>Đánh giá</h4>
        </div>
    </nav>
    <div class="info-group info" id="inf"  >
        <span>
            {!! $product->description !!}
        </span>
    </div>
    <div class="info-group list-review" id="review" >
        <h3> Đánh giá sản phẩm </h3>    
        <div class="detail evaluate " style="margin-bottom: 20px; display:flex; align-items: baseline">
            <div class="evaluate-start">
                <div class="rating-display" id="rating-display-{{ $product->id }}">
                    @for($count = 1; $count <= 5; $count++)
                        <span class="star" data-value="{{ $count }}" style="color: {{ $count <= $averageRating ? '#ff97a0' : '#ccc' }}">&#9733;</span>
                    @endfor
                </div>
            </div>
            <small class="pt-1" style="margin-left: 15px">({{ $totalRatings }} đánh giá )</small>
        </div>
        @if($ratings->isEmpty())
        <p>Chưa có đánh giá nào cho sản phẩm này.</p>
        @else
        @foreach($ratings as $rating)
     
        <div class="group-user">
            <div class="avatar">
                @if(!$rating->user->avatar)
                    <img src="/template/asset/image/baseAvatar.jpg" class="account__avatar">
                
                @else
                    <img src="{{ $rating->user->avatar }}" class="account__avatar">
                
                @endif
            </div>
            <div class="comment-content">
                <div>
                    <h5> {{ $rating->user->name }} -<i> {{ \Carbon\Carbon::parse($rating->date)->format('d/m/Y') }} </i></h5>
                </div>
                <div>
                    <p>
                        @for($count = 1; $count <=  $rating->rating; $count++)
                            <span class="star {{ $count <= $rating->rating ? 'selected' : '' }}">&#9733;</span>
                        @endfor
                    </p>
                </div>
                <div>
                    <span>
                         {{ $rating->comment }}
                    </span>
                </div>
            </div>
        </div>
             
        @endforeach
    @endif
    </div>
</div>



<div class="trendy-products">
    <h2>
        <hr  width="100%" size="5px" align="left" color="pink" />
        CÓ THỂ BẠN CŨNG THÍCH
        <hr  width="100%" size="5px" align="left" color="pink" />
    </h2>
    <div class="list-products"  id="product-list">
            @foreach($list_products as $product)
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
    </div>
</div>



@endsection

<script>
    const warehouseItems = [
        @foreach ($warehouse_items as $item)
            {
                size_id: {{ $item->size_id }},
                color_id: {{ $item->color_id }},
            },
        @endforeach
    ];
</script>
