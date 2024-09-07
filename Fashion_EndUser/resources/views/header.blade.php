<div class="header">
    <div class=" container header-bottom">
        <div class="row">
            <div class="col-xs-3 col-sm-3 col-lg-3 img-logo">
                <img src="/template/asset/image/logo.png" alt="logo">
                <a href="/">AH FASHION</a>
            </div>
            <form action="/search" class="col-xs-3 col-sm-3 col-lg-3 search">
                <input type="text" name="keyword" id="search" placeholder="Tìm kiếm sản phẩm" autocomplete="off">
                <button type="submit">
                    <i class="fa fa-search" aria-hidden="true" style="top:29px"></i>
                </button>
                <div class="search-history" id="search-history">
                    <!-- Lịch sử tìm kiếm sẽ được thêm vào đây -->
                </div>          
            </form>
            
            <div class="col-xs-3 col-sm-3 col-lg-3 cart">
                <span>
                    @if(Auth::check())  
                    @php
                        $user = FashionCore\Models\User::with(['cart', 'cart.cart_items'])->find(Auth::id());
                        $cartItemCount = $user->cart ? $user->cart->cart_items->count() : 0;
                    @endphp
                    <a href="/cart/index">
                        <i class="fa fa-shopping-cart" aria-hidden="true"></i>
                        <div class="cart__quantity">{{ $cartItemCount }}</div>
                    </a>
                    @else
                    <a href="/cart/index">
                        <i class="fa fa-shopping-cart" aria-hidden="true"></i>
                        <div class="cart__quantity">0</div>
                    </a>
                    @endif
                </span>
            </div>
            <div class="col-xs-3 col-sm-3 col-lg-3 info-account">            
                @if(Auth::check())  
                <div class="content-dropdown bell">
                    <div class="number">{{ $countNotification }}</div>
                    <i class="fa-regular fa-bell"></i>
                    <div class="dropdown-container dropdown-bell" id="dropdown-container">
                        @if($getNotications->isEmpty())
                            <div class="title">
                                <p>Chưa có thông báo</p>
                            </div>
                        @else
                            @foreach($getNotications as $item)
                            <a href="#" class="notification-item">
                                <div class="title">
                                    <p>{{ $item->title }}</p>
                                </div>
                                <div class="content-notify">
                                    <div>{!! $item->message !!}</div>
                                </div>
                                <div class="time">
                                    <span> {{ \Carbon\Carbon::parse($item->date_received)->format('H:i d/m/Y') }} </span>
                                </div>
                            </a>
                            @endforeach
                            <div class="border-bottom"></div>
                            {{-- xem tat ca --}}
                            <div class="all-notify" style="text-align: center; font-size: 10px; padding:7px">
                                <a href="">Xem tất cả</a> 
                            </div>
        
                        @endif
                    </div>
                </div>

                <div class="account">
                    <img src="{{ Auth::user()->avatar ?? '/template/asset/image/baseAvatar.jpg' }}" class="account__avatar"> 

                    <span>{{ Auth::user()->username }}</span> 
                    <div class="dropdown account__dropdown">
                        <a href="/account/index">Thông tin cá nhân</a>
                        <a href="/cart/index">Giỏ hàng</a>
                        <a href="{{ route('home.order-history') }}">Đơn hàng của tôi</a>
                        <a href="{{ route('custom.logout') }}"  class="header__auth">Đăng xuất</a>
                    </div>
                </div>
                @else
                    <a href="{{ route('login') }}" class="header__auth">Đăng nhập</a>
                    <a href="register" class="header__auth">Đăng ký</a>
                @endif
            </div>
        </div>
    </div>
</div>
