@extends('main')

@section('content')
   
    <div class="container">
        <div class="account__text-header">
            <p>
                <a href="{{route ('home.index') }}">Trang chủ
                    <i class="fa-solid fa-angles-right"></i>
                </a> 
                {{ $category->name }}
            </p>
        </div>
     
        <div class="row">
            <div class="product-body trendy-products">
                <div class="list-products">
                     @foreach($products as $product)
                        <div class="item-product">
                            <img src="{{$product->images->first()->url}}"><br>
                            <div>
                                 <div class="name-product">
                                    <p>{{ $product->name }}</p><br>
                                    </div>
                                        <div class="price-product"> 
                                            {!! price($product->warehouse_items->first()->sell_price, $product->warehouse_items->first()->sale_price) !!}
                                            {{-- <p class="price-new">{{ number_format(($product->warehouse_items->first()->sell_price ?? 0), 2) }}
                                            <del class="price-old">{{ number_format(($product->warehouse_items->first()->sale_price ?? 0), 2) }}</del>
                                            </p> --}}
                                        </div>
                                        <div class="option">
                                            <div class="view-details">
                                                <a  href="/product/detail/{{ $product->id }}-{{ Str::slug($product->name, '-') }}.html" >
                                                    <i class="fa fa-eye" aria-hidden="true"></i>
                                                    Xem chi tiết
                                                </a>
                                            </div>
                                            {{-- <form action="{{ route('create-cart') }}" method="POST" class="add-to-cart">
                                                @csrf
                                                <input type="hidden" name="id" value="{{ $product->id }}">
                                                <input type="hidden" name="num_product" value="1">
                                                <button type="submit" class="btn btn-sm"><i class="fas fa-shopping-cart"></i>Add To Cart</button>
                                            </form> --}}
                                        </div>
                                    </div>
                                </div>
                                
                                @endforeach
                            </div>
                        </div>
                </div>
            </div>
            {{ $products->appends(request()->query())->links('pagination::bootstrap-4') }}

    </div>



@endsection
