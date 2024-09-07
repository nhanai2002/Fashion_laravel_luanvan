$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});


function sendMessage() {
    const userId = document.querySelector('meta[name="user-id"]').getAttribute('content'); 

    var messageInput = document.getElementById('message-input');
    var messageContent = messageInput.value.trim();
    if (messageContent) {
        $.ajax({
            type: "POST",
            dataType: "JSON",
            data: {
                message: messageContent,
                user_id: userId,
            },
            url: '/send-message', 
            cache: false,
            success: function(response) {
                console.log('Message sent successfully');
                messageInput.value = '';c
            },
            error: function(error) {
                console.error('Error sending message:', error);
            }
        });
    }
}


function updateChatWindow(data) {
    const userId = parseInt(document.querySelector('meta[name="user-id"]').getAttribute('content'), 10); 

    const chatBody = document.getElementById('chat-body');
    const messageDiv = document.createElement('div');
    const namediv = document.createElement('div');
    const messageContainer = document.createElement('div');
    messageContainer.className = 'message__containner';
    namediv.className = 'name-chat';
    if (data.user == null) {
        messageDiv.className = 'admin_message';
        namediv.textContent = 'Tin nhắn tự động';
        messageDiv.textContent = data.messageContent.content;
    }
    else if (data.user.id == userId) {
        messageDiv.className = 'user_message';
        messageDiv.textContent = data.messageContent.content;
    } else {
        messageDiv.className = 'admin_message';
        namediv.textContent = 'Nhân viên ' + data.user.name;
        messageDiv.textContent = data.messageContent.content;
    }
    messageContainer.appendChild(namediv);
    messageContainer.appendChild(messageDiv);
    chatBody.appendChild(messageContainer);

    const timeSpan = document.createElement('span');
    timeSpan.textContent = formatDate(data.messageContent.created_at);
    timeSpan.className = 'message-time'; 
    messageDiv.appendChild(timeSpan);
    var messageInput = document.getElementById('message-input');
    messageInput.value = '';
    chatBody.scrollTop = chatBody.scrollHeight;  
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

function checkEnterKey(event) {
    if (event.key === 'Enter') {
        event.preventDefault(); 
        sendMessage();
    }
}

function toggleChatWindow() {
    var chatWindow = document.getElementById('chat-window');
    const chatBody = document.getElementById('chat-body');

    if (chatWindow.style.display === 'none' || chatWindow.style.display === '') {
        chatWindow.style.display = 'flex';
        setTimeout(() => {
            chatBody.scrollTop = chatBody.scrollHeight;
        }, 0);
    } else {
        chatWindow.style.display = 'none';
    }
}

