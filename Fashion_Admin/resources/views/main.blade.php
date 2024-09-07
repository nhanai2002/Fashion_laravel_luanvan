<!DOCTYPE html>
<html lang="en">
    @include('head')
    @yield('head')
<body>
    @include('sidebar')
    <div class="content">
        @include('header')
        <div class="content-body">
            @include('alert')

            @yield('content')
            @include('chat')
        </div>
        </div>
    </div>

   
   
   {{-- <script>
         var menu = document.querySelectorAll('#dropdown > li');
        // Lặp qua từng menu để gán sự kiện click
        for (var i = 0; i < menu.length; i++)
        {
            menu[i].addEventListener("click", function()
            {
                var menuList = document.querySelectorAll('#dropdown > li > ul');
                for (var j = 0; j < menuList.length; j++) {
                    menuList[j].style.display = "none";
                }
                this.children[1].style.display = "grid";
            });
        }
    </script> --}}
    @include('footer')
    @yield('footer')

</body>
</html>