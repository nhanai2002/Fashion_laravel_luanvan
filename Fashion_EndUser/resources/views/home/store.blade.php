@extends('main')

@section('content')

<div class="container">
    <div class="account__text-header">
        <p>
            <a href="{{ Route('home.index') }}">Trang chủ
            <i class="fa-solid fa-angles-right"></i>
            </a> 
            Cửa hàng
        </p>
    </div>
        <div>
            <img src="/template/asset/image/Rose et Beige Fleurs Moderne Artisan Entreprise X-Frame Bannière (1).png" alt="" width="100%">
        </div>  
        <div class="row store__list">
            <form class="tree-most" id="form__check" action="{{ route('store') }}" method="GET">
                <input type="hidden" id="csrf-token" name="_token" value="">
                <div class="col-xs-4 col-sm-4 col-lg-4 ">
                        <div class="filter price">
                            <h4>Lọc theo giá</h4>
                            <div class="box">
                                <div>
                                    <input type="checkbox" name="price[]" id="price_1" value="0-100000" class="filter-checkbox"
                                    {{--Kiểm tra xem giá trị 0-100.000 có nằm trong mảng giá trong request không --}}
                                    {{ in_array('0-100000', request('price', [])) ? 'checked' : '' }}>
                                    <label for="price_1">0 - 100.000</label>
                                </div>
                            </div>
                            <div class="box">
                                <div>
                                    <input type="checkbox" name="price[]" id="price_2" value="100000-200000" class="filter-checkbox"
                                    {{ in_array('100000-200000', request('price', [])) ? 'checked' : '' }}>
                                    <label for="price_2">100.000 - 200.000</label>
                                </div>
                            </div>
                            <div class="box">
                                <div>
                                    <input type="checkbox" name="price[]" id="price_3" value="200000-300000" class="filter-checkbox"
                                    {{ in_array('200000-300000', request('price', [])) ? 'checked' : '' }}>
                                    <label for="price_3">200.000 - 300.000</label>
                                </div>
                            </div>
                            <div class="box">
                                <div>
                                    <input type="checkbox" name="price[]" id="price_4" value="300000-400000" class="filter-checkbox"
                                    {{ in_array('300000-400000', request('price', [])) ? 'checked' : '' }}>
                                    <label for="price_4">300.000 - 400.000</label>
                                </div>
                            </div>
                        </div>
                        <div class="filter color">
                            <h4>Lọc theo màu</h4>
                            @foreach($colors as $color)
                            <div class="box">
                                <div>
                                    <input type="checkbox" class="filter-checkbox" name="color[]" id="color_{{ $color->id }}" value="{{ $color->id }}"
                                    {{ in_array($color->id, request('color', [])) ? 'checked' : '' }}>
                                    <label for="color_{{ $color->id }}">{{ $color->name }}</label>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <div class="filter size">
                            <h4>Lọc theo size</h4>
                            @foreach($sizes as $size)
                            <div class="box">
                                <div>
                                    <input type="checkbox" class="filter-checkbox" name="size[]" id="size_{{ $size->id }}" value="{{ $size->id }}"
                                    {{ in_array($size->id, request('size', [])) ? 'checked' : '' }}>
                                    <label for="size_{{ $size->id }}">{{ $size->name }}</label>
                                </div>
                            </div>
                            @endforeach
                        </div>
                </div>
                <div class="col-xs-8 col-sm-8 col-lg-8 ">
                    <div class="product-header">
                        <!-- Thanh tìm kiếm -->
                        {{-- <div class="search">
                            <input type="text" id="" value="" placeholder="Tìm kiếm theo tên">
                            <button>
                                <i class="fa fa-search" aria-hidden="true"></i>
                            </button>
                        </div> --}}
                        <!-- Sắp xếp -->
                        <div class="sort">
                            <div class="dropdown">
                                <select class="dropdown-sort select-filter orderby" name="orderby">
                                            {{-- Kiểm tra giá trị 'orderby' trong request là gì --}}
                                    <option {{ Request::get('orderby') == 'default' ? "selected='selected'" : '' }}
                                        value="default">Sắp xếp</option>
                                    <option {{ Request::get('orderby') == 'asc' ? "selected='selected'" : '' }}
                                        value="asc">Giá tăng dần</option>
                                    <option {{ Request::get('orderby') == 'desc' ? "selected='selected'" : '' }}
                                        value="desc">Giá giảm dần</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="product-body trendy-products">
                        <div class="list-products">
                            @foreach ($products as $product)
                                <div class="item-product">
                                    <img src="{{ $product->images->first()->url }}"><br>
                                    <div>
                                        <div class="name-product">
                                            <p>{{ $product->name }}</p><br>
                                        </div>
                                        <div class="price-product">
                                            {!! price(
                                                $product->warehouse_items->first()->sell_price,
                                                $product->warehouse_items->first()->sale_price,
                                            ) !!}
                                            {{-- <p class="price-new">{{ number_format(($product->warehouse_items->first()->sell_price ?? 0), 2) }}
                                                <del class="price-old">{{ number_format(($product->warehouse_items->first()->sale_price ?? 0), 2) }}</del>
                                                </p> --}}
                                        </div>
                                        <div class="option">
                                            <div class="view-details">
                                                <a
                                                    href="/product/detail/{{ $product->id }}-{{ Str::slug($product->name, '-') }}.html">
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
                    <nav>
                        {{-- thêm các tham số truy vấn vào liên kết phân trang --}}
                        {{ $products->appends(request()->query())->links('pagination::bootstrap-4') }}
                    </nav>
                </div>
                <input type="hidden" name="page" id="page" value="1">
            </form>
        </div>
    </div>
  
@endsection
