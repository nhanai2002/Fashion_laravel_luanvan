@include('head')
<div class="form-login">
    <form action="" method="POST">
        @csrf
        <div>
            <h2>Đổi mật khẩu</h2>
        </div>
        @include('alert') 
        <div>
            <input type="text" value="{{ $username }}" placeholder="Tài khoản" readonly>
        </div>
        <div>
            <input type="password" name="password" placeholder="Mật khẩu">
        </div>
        <div>
            <input type="password" name="password_confirmation" placeholder="Nhập lại mật khẩu">
        </div>
        <div>
            <input type="submit" id="submit" value="Đổi mật khẩu">
        </div>
    </form>
</div>
