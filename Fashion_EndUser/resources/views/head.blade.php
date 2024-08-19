<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/template/asset/css/computerStyle.css">
    <link rel="stylesheet" href="/template/asset/css/mobileStyle.css">
    <link rel="stylesheet" href="/template/asset/css/tabletStyle.css">
    <link rel="stylesheet" href="/fontawesome-free-6.4.2-web/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="/template/asset/js/topNavMenu.js"></script>
    <script src="/template/asset/js/detailSlider.js"></script>
    <script src="/template/asset/js/main.js"></script>
    <script src="/template/asset/js/quantityCart.js"></script>
    <script src="/template/asset/js/dropdown.js"></script>
    <script src="/template/asset/js/sortProducts.js"></script>
    <script src="/template/asset/js/subnavDetails.js"></script>
    <script src="/template/asset/js/checkSizeColor.js"></script>
    <script src="/template/asset/js/rating.js"></script>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <title>{{ $title }}</title>

    @vite(['resources/js/bootstrap.js'])
    <meta name="user-id" content="{{ Auth::id() }}">

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>
    @yield('head')
</head>