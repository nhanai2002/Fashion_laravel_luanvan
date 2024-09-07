@if(Auth::check())
<div id="chat-widget" class="chat-widget">
    <div class="chat-icon" onclick="toggleChatWindow()">
        <i class="fa-regular fa-comment-dots" style="font-size: 30px;color: white;"></i>
    </div>
    <div id="chat-window" class="chat-window">
        <div class="chat-header">
            <div class="chat-header-info">
                <img id="user-avatar" src="/template/asset/image/logo.png" alt="Avatar" class="account__avatar">
                <span style="margin-left: 10px">FashionAH</span>
            </div>
            <button class="close-btn" onclick="toggleChatWindow()">x</button>
        </div>
        <div class="chat-body" id="chat-body">
        @if($conversation)
            @if($conversation->messages)
            @foreach($conversation->messages as $message)
            <div class="message__containner">
                @if($message->sender_id == null)
                    <div class="name-chat">Tin nhắn tự động</div>
                @elseif($message->user  && $message->sender_id != Auth::id())
                    <div class="name-chat">Nhân viên {{ $message->user->name }}</div>
                @endif
                <div class="{{ $message->sender_id == Auth::id() ? 'user_message' : 'admin_message' }}">
                    {{ $message->content }}
                    <span class="message-time">
                        {{ $message->created_at->format('H:i d/m/Y') }}
                    </span>
                </div>
            </div>
            @endforeach
            @endif
        @endif
        </div>
        <div class="chat-footer">
            <input type="text" id="message-input" onkeydown="checkEnterKey(event)" placeholder="Type a message...">
            <button onclick="sendMessage()">
                <i class="fa-solid fa-paper-plane"></i>
            </button>
        </div>
    </div>
</div>
@endif
