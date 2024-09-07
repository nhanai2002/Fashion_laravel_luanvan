document.addEventListener('DOMContentLoaded', function() {
    const chatButtons = document.querySelectorAll('.chat-button');
    chatButtons.forEach(button => {
        button.addEventListener('click', function(event) {
            event.preventDefault();
            const userId = this.getAttribute('data-user-id');
            localStorage.setItem('activeChatUserId', userId);
            loadChatWindow(userId); // Load hội thoại của user đó
        });
    });
    const activeChatUserId = localStorage.getItem('activeChatUserId');
    if (activeChatUserId) {
        loadChatWindow(activeChatUserId);
    }
});



function loadChatWindow(userId) {
    $.ajax({
        url: `/admin/chat/view-chat/${userId}`,
        type: 'GET',
        dataType: 'JSON',
        success: function(data) {
            const userId = document.querySelector('meta[name="user-id"]').getAttribute('content'); 
            const chatWindow = document.getElementById('chat-window');
            chatWindow.style.display = 'flex';
            document.getElementById('conversation-id-chat').value = data.conversation.id;

            
            if(data.conversation.id){
                document.getElementById('chat-widget').style.display = 'flex';
            }
            updateChatWindow(data);
            window.Echo.private('conversation.' + data.conversation.id)
                .listen('.MessageSentEvent', function (event) {
                    const chatBody = document.getElementById('chat-body');
                    const messageDiv = document.createElement('div');
                    const namediv = document.createElement('div');
                    const messageContainer = document.createElement('div');
                    messageContainer.className = 'message__containner';
                    namediv.className = 'name-chat';   
                    if(event.user == null){
                        namediv.textContent = 'Tin nhắn tự động';
                        messageDiv.className = 'user_message';
                    }
                    else if(event.user.id == userId){
                        namediv.textContent = event.user.name;
                        messageDiv.className = 'user_message';
                    }
                    else {
                        messageDiv.className = 'admin_message';
                    }

                    messageDiv.textContent = event.messageContent.content;
                    messageContainer.appendChild(namediv);
                    messageContainer.appendChild(messageDiv);
                    chatBody.appendChild(messageContainer);

                    const timeSpan = document.createElement('span');
                    timeSpan.textContent = formatDate(event.messageContent.created_at);
                    timeSpan.className = 'message-time'; 
                    messageDiv.appendChild(timeSpan);
                    var messageInput = document.getElementById('message-input');
                    messageInput.value = '';
                    
                    chatBody.scrollTop = chatBody.scrollHeight; 
                     
                });
            var idHumanTakeOver = data.humanTakingOver.user.id;
            if(idHumanTakeOver && idHumanTakeOver != userId){
                checkWhoChat(2, data.humanTakingOver.user.name)
            }
        },
        error: function(status, error) {
            console.error('Error fetching conversation:', status, error);
        }
    });
}

function updateChatWindow(data) {
    const userAvatar = document.getElementById('user-avatar');
    const userName = document.getElementById('user-name');

    userAvatar.src = data.user.avatar ?? '/template/asset/image/baseAvatar.jpg';

    userName.textContent = data.user.name ?? data.user.username;


    const chatBody = document.getElementById('chat-body');
    chatBody.innerHTML = '';
    checkWhoChat(data.conversation.is_taken_over);
    data.messages.forEach(message => {
        const messageDiv = document.createElement('div');
        const namediv = document.createElement('div');
        const messageContainer = document.createElement('div');
        messageContainer.className = 'message__containner';
        namediv.className = 'name-chat';

        if(message.sender_id == null){
            messageDiv.className = 'user_message';
            namediv.textContent = 'Tin nhắn tự động'
        }
        else{
            messageDiv.className = message.sender_id === data.user.id ? 'admin_message' : 'user_message';
            if(message.sender_id != data.user.id){
                namediv.textContent = message.user.name ?? message.user.username;
            }
        }
        
        messageDiv.textContent = message.content;
        messageContainer.appendChild(namediv);
        messageContainer.appendChild(messageDiv);
        chatBody.appendChild(messageContainer);
        
        const timeSpan = document.createElement('span');
        timeSpan.textContent = formatDate(message.created_at);
        timeSpan.className = 'message-time'; 
        messageDiv.appendChild(timeSpan);
    });
    chatBody.scrollTop = chatBody.scrollHeight; 
}

function checkEnterKey(event) {
    if (event.key === 'Enter') {
        event.preventDefault(); 
        sendMessage(); 
    }
}

function sendMessage() {
    const userId = document.querySelector('meta[name="user-id"]').getAttribute('content'); 
    const id = document.getElementById('conversation-id-chat').value;

    var messageInput = document.getElementById('message-input');
    var messageContent = messageInput.value.trim();
    if (messageContent) {
        $.ajax({
            type: "POST",
            dataType: "JSON",
            data: {
                conversation_id: id,
                message: messageContent,
                user_id: userId,
            },
            url: '/admin/chat/send-message', 
            cache: false,

            success: function(response) {
                messageInput.value = '';
            },
            error: function(error) {
                console.error('Error sending message:', error);
            }
        });
    }
}



function formatDate(dateString) {
    const options = { 
        hour: '2-digit', 
        minute: '2-digit', 
        day: '2-digit', 
        month: '2-digit', 
        year: 'numeric' 
    };
    const date = new Date(dateString);
    return date.toLocaleString('vi-VN', options); 
}


function checkWhoChat(is_taken_over, name){
    const chuyengiaoElement = document.getElementById('handover-button');
    const tiepquanElement = document.getElementById('handover-to-bot-button');
    const divChatFooterElement = document.getElementById('admin-chat-btn');

    if(parseInt(is_taken_over) === 2){
        tiepquanElement.textContent = name + ' hiện đang tiếp quản cuộc trò chuyện này';
        tiepquanElement.disabled = true;
        chuyengiaoElement.style.display = 'none';
        divChatFooterElement.style.display = 'none';
        tiepquanElement.style.display = 'block';
    }
    if(parseInt(is_taken_over) === 1  && (chuyengiaoElement.style.display === 'none' || chuyengiaoElement.style.display === '')){
        chuyengiaoElement.style.display = 'block';
        divChatFooterElement.style.display = 'block';
        tiepquanElement.style.display = 'none';
    }
    else if(parseInt(is_taken_over) === 0  && chuyengiaoElement.style.display === 'block'){
        chuyengiaoElement.style.display = 'none';
        divChatFooterElement.style.display = 'none';
        tiepquanElement.style.display = 'block';
    }
}

function toggleChatWindow(status) {
    var chatwidget = document.getElementById('chat-widget');
    var chatWindow = document.getElementById('chat-window');
    var chatBody = document.getElementById('chat-body');

    if(status === 0){
        if (chatwidget.style.display === 'none' || chatwidget.style.display === '') {
            chatwidget.style.display = 'flex';
            chatBody.scrollTop = chatBody.scrollHeight;
        }
    }
    else if(status === 1){
        chatWindow.style.display = 'none';
    }else if(status === 2){
        chatwidget.style.display = 'none';
        localStorage.removeItem('activeChatUserId');
    }
    
}

function changeStatusTakeOver(stt){
    const id = document.getElementById('conversation-id-chat').value;
    if(id){
        $.ajax({
            type: 'POST',
            datatype:'JSON',
            data:{
                id: id,
                stt: stt,
            },
            url: '/admin/chat/change-status',
            success: function(result){  
                checkWhoChat(result.is_taken_over);
                if(result.error){
                    alert(result.message);
                }
            },
            error: function(status, error) {
                console.error('Error fetching conversation:', status, error);
            }
        });
    }
}
