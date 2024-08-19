<div class="row">
    <div class="col-xs-4 col-sm-4 col-lg-4 navbar">
        <div class="dropdown">
            <button class="btn btn-primary dropdown-toggle btnDrop" onclick="toggleVisibility()" type="button" ">
                 Danh mục
                 <i class="fa fa-sort-desc" aria-hidden="true"></i>
            </button>
            <div id="menu-drop" class="menu">
               {!! listCategoryView($categories) !!}
            </div>
        </div>

    </div>
    <div class="col-xs-8 col-sm-8 col-lg-8 slider">
        <div class="menu topnav" id="top-nav"> 
            <a href="{{ route('home.index') }}">Trang chủ</a>
            <a href="{{ route('store') }}">Cửa hàng</a>
            <a href="{{ route('recruitment') }}">Tuyển dụng</a>
            <a href="{{ route('contact') }}">Liên hệ</a>
            <a href="javascript:void(0);" class="icon" onclick="navBar()">
                <i class="fa fa-bars"></i>
            </a>
            @if(Auth::check())  
            <div class="account login">
                <a href="/account/index">Thông tin cá nhân</a>
                <a href="/cart/index">Giỏ hàng</a>
                <a href="/home/order-history">Đơn hàng của tôi</a>
                <a href="{{ route('custom.logout') }}"  class="header__auth">Đăng xuất</a>
            @else
                <a href="{{ route('login') }}" class="header__auth login">Đăng nhập</a>
            @endif
            </div>
           
        </div>
       
    </div>
</div>
<script>
    var btn = document.getElementsByClassName('btn');
    var menu = document.getElementById('menu-drop');

    btn.addEventListener('click', function() {
        menu.classList.toggle('active');
    });
</script>
