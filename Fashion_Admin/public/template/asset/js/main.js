// code ở đây

// do dùng ajax gửi theo post nên cần token của form mới gửi đc
// nên copy phần này bỏ zô file head 
    //<meta name="csrf-token" content="{{ csrf_token() }}">

// và copy phần này bỏ vào file js

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

function removeRow(id, url){
    if(confirm('Bạn có chắc chắn xóa?')){
        $.ajax({
            type: 'DELETE',
            datatype:'JSON',
            data:{
                id: id,
            },
            url: url,
            success: function(result){
                if(result.error == false){
                    alert(result.message);
                    location.reload();
                }
                else if(result.error == true){
                    alert(result.message);
                    location.reload();
                }
                else{
                    alert('Đã xảy ra lỗi!');
                }
            }
        });
    }
}

function changeStatus(id, url){
    $.ajax({
        type: 'POST',
        datatype:'JSON',
        data:{
            id: id,
        },
        url: url,
        success: function(result){
            if(result.error == false){
                location.reload();
            }
            else if(result.error == true){
                alert(result.message);
            }
            else{
                alert('Đã xảy ra lỗi!');
            }
        }
    });
}

function loadMore(){
    const page = $('#page').val();
    $.ajax({
        type: 'POST',
        dataType: 'JSON',
        data:{
            page : page
        },
        url: '/services/load-product',
        success: function (result) {
            if(result.html != ''){
                $('#loadProducts').append(result.html);
                $('#page').val(page + 1);
            }
            else{
                alert('Đã load xong sản phẩm');
                $('#button-loadMore').css('display', 'none');
            }
        }
        
    });
}


// Hàm để thêm thông báo mới
function updateNotificationCount(){
    // Cập nhật số lượng thông báo chưa xem
    let numberElement = document.querySelector('.bell .number');
    let currentCount = parseInt(numberElement.textContent, 10) || 0;
    numberElement.textContent = currentCount + 1;
}

function addNewNotificationToTop(notification){
    const notificationContainer = document.getElementById('dropdown-container');
    const bellIcon = document.querySelector('.bell');
    const notificationItem = document.createElement('div');
    const truncatedMessage = truncateMessage(notification.message, 100); // Giới hạn 100 ký tự

    notificationItem.classList.add('notification-item', 'new-notification');
    notificationItem.innerHTML = `
        <a href="#" class="notification-item">
            <div class="title">
                <p>${notification.title}</p>
            </div>
            <div class="content-notify">
                <div>
                    ${truncatedMessage}
                </div>
            </div>
            <div class="time"> 
                <span> Vừa mới </span>
            </div>
            <div class="border-bottom"></div>
        </a>
    `;
    notificationContainer.prepend(notificationItem);


    bellIcon.classList.add('ringing');
    setTimeout(() => {
        bellIcon.classList.remove('ringing');
        bellIcon.classList.add('ringing');
        setTimeout(() => {
            bellIcon.classList.remove('ringing');
        }, 500);
    }, 500);
}

function truncateMessage(message, maxLength) {
    if (message.length > maxLength) {
        return message.substring(0, maxLength) + '...';
    } else {
        return message;
    }
}



