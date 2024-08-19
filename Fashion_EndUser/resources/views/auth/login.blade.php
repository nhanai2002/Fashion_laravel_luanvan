@include('head')
<div class="form-login">
    <form action="login" method="POST">
        @csrf
        <div>
            <h2>Đăng nhập</h2>
        </div>
        @include('alert') 
        <div>
            <input type="text" name="username" placeholder="Tài khoản">
        </div>
        <div>
            <input type="password" name="password" placeholder="Mật khẩu">
        </div>
        <div>
            <input type="submit" id="submit" value="ĐĂNG NHẬP">
        </div>
        <div>
            <a href="{{ route('auth.google') }}">Đăng nhập với <i class="fa-brands fa-google">oogle</i></a>
            <span>
                <a href="forgot-password">Quên mật khẩu?</a> 
                hoặc 
                <a href="register"> Đăng ký</a>
            </span>
        </div>
    </form>
</div>
