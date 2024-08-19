<!DOCTYPE html>
<html lang="en">
<head>
    @include('head')
    @yield('head')
</head>
<body>
    @include('header')

    <div class="content">
        <div class="container">
            @include('menu')

            @yield('slider')
            @yield('header-order')
            @include('alert')
            @yield('content')
        </div>
    </div>

    @include('footer')
    
</body>
</html>