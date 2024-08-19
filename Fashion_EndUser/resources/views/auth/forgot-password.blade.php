@include('head')
<div class="form-login">
    <form action="" method="POST">
        @csrf
        <div>
            <h2>Quên mật khẩu</h2>
        </div>
        @include('alert') 
        <div>
            <input type="text" name="username" placeholder="Tài khoản">
        </div>
        <div>
            <input type="submit" id="submit" value="Gửi">
        </div>
    </form>
</div>
