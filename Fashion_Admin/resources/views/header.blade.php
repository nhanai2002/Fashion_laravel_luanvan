<div class="content-top">
    <div id="content-subject">
        <p>{{ $title }}</p>
    </div>
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
                        <div>
                            {!! $item->message !!}
                        </div>
                    </div>
                    <div class="time"> 
                        <span> {{ \Carbon\Carbon::parse($item->date_received)->format('H:i d/m/Y') }} </span>
                    </div>
                </a>
                @endforeach
                <div class="border-bottom"></div>
                    {{-- xem tat ca --}}
                <div class="all-notify" style="text-align: center; font-size: 10px">
                    <a href="">Xem tất cả</a> 
                </div>
            @endif
        </div>
    </div>

    <div class="content-dropdown" >
        <img src="{{ Auth::user()->avatar ?? '/template/asset/image/baseAvatar.jpg' }}" class="account__avatar"> 
            {{  Auth::user()->username }}
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" clip-rule="evenodd" d="M16.2071 9.79289C15.8166 9.40237 15.1834 9.40237 14.7929 9.79289L12 12.5858L9.20711 9.79289C8.81658 9.40237 8.18342 9.40237 7.79289 9.79289C7.40237 10.1834 7.40237 10.8166 7.79289 11.2071L11.2929 14.7071C11.6834 15.0976 12.3166 15.0976 12.7071 14.7071L16.2071 11.2071C16.5976 10.8166 16.5976 10.1834 16.2071 9.79289Z" fill="currentColor"/>
            </svg>
            <div class="dropdown-container" id="dropdown-container">
                <a href="{{ route('account.index') }}">
                    <i class="fa-solid fa-gears"></i>
                    Tài khoản
                </a>
                <a href="/admin/logout">
                    <i class="fa-solid fa-right-from-bracket"></i>
                    Đăng xuất
                </a>
            </div>
    </div>
    
</div>
