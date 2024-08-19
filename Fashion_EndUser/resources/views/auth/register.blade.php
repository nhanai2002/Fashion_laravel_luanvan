@include('head')

<div class="form-login">
    <form action="register" method="POST">
        @csrf
        <div>
            <h2>Đăng ký</h2>
        </div>
        @include('alert') 
        <div>
            <input type="text" name="username" placeholder="Tài khoản">
        </div>
        <div>
            <input type="text" name="name" placeholder="Họ tên">
        </div>
        <div>
            <input type="text" name="phone" placeholder="Số điện thoại">
        </div>   
        <div>
            <input type="email" name="email" placeholder="Email">
        </div>
        <div>
            <input type="password" name="password" placeholder="Mật khẩu">
        </div>
        <div>
            <input type="password" name="password_confirmation" placeholder="Nhập lại mật khẩu">
        </div>
        <div>
            <input type="submit" id="submit" value="ĐĂNG KÝ">
        </div>
        <div>
            <span>
                Đã có tài khoản?
                <a href="login"> Đăng nhập</a>
            </span>
        </div>
        </div>
    </form>
</div>
