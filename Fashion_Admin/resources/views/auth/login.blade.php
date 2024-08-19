<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/template/asset/css/style.css">

    <title>{{ $title }}</title>
</head>
<body style="background-color: rgba(202, 202, 202, 1);">
    <form action="/admin/login" method="POST" class="login">
        @csrf
        <b>AH FASHION</b>
        <p class="welcome">Đăng nhập</p>
        <div>
            <h5 style="color: red">
                @include('alert')
            </h5>    
            <label> Tài khoản:</label><br>
            <input type="text" name="username"><br>
            <label> Mật khẩu:</label><br>
            <input type="password" name="password"><br>
            <div class="submit">
                <input type="submit" value="Đăng nhập">
            </div>
        </div>
    </form>

</body>
</html>