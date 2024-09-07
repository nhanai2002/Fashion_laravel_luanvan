
<div id="chat-widget" class="chat-widget" style="display: none">
    <div class="chat-icon" onclick="toggleChatWindow(0)">
        <i class="fa-regular fa-comment-dots" style="font-size: 30px;color: white;"></i>
    </div>
    <div id="chat-window" class="chat-window">
        <input type="hidden" id="conversation-id-chat" value="">
        <div class="chat-header">
            <div class="chat-header-info">
                <img id="user-avatar" src="/template/asset/image/baseAvatar.jpg" alt="Avatar" class="account__avatar">
                <span id="user-name" style="margin-left: 10px"></span>
            </div>
            <button id="handover-button" class="chat-action-button handover-button" style="display: block;" onclick="changeStatusTakeOver(0)">
                Chuyển giao
                <i class="fa-solid fa-robot"></i>
            </button>
            <div class="display:flex">
                <button class="close-btn" onclick="toggleChatWindow(1)">-</button>
                <button class="close-btn" onclick="toggleChatWindow(2)">x</button>    
            </div>
            
        </div>
        <div class="chat-body" id="chat-body">
            {{-- Nội dung tin nhắn --}}
        </div>
        <div class="chat-footer">
            <div id="admin-chat-btn" >
                <input type="text" id="message-input" onkeydown="checkEnterKey(event)" placeholder="Type a message..." style="width:85%">
                <button onclick="sendMessage()">
                    <i class="fa-solid fa-paper-plane"></i>
                </button>    
            </div>
            <button id="handover-to-bot-button" class="chat-action-button handover-button" style="display: none;" onclick="changeStatusTakeOver(1)">
                Tiếp quản
            </button>    
        </div>
    </div>
</div>
