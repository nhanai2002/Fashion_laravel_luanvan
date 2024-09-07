$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
function updateCart(cartItemId, add){
    $.ajax({
        data:{
            id: cartItemId,
            add: add,
        },
        type: "POST",
        dataType: "JSON",
        url: "/cart/update/",
        success: function(response){
            if(response.error === false){
            }
            else{
                alert(response.message);
            }
        },
        error: function (error) {
            //alert(error.message);
            },
        complete: function () {
            location.reload();
        }
    });
}

function removeRow(id, url){
    if(confirm('Bạn chắc chắn xóa?')){
        $.ajax({
            type: 'DELETE',
            datatype:'JSON',
            data:{
                id: id,
            },
            url: url,
            success: function(result){
                if(result.error == false){
                    window.location.reload();
                }
                else{
                    alert('Đã xảy ra lỗi!');
                }
            }
        });
    }
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

function applyCoupon() {
    const coupon = $("#coupon").val();
    const cartId = $("#cartId").val();
    $.ajax({
        type: "POST",
        dataType: "JSON",
        data: {
            coupon: coupon,
            cartId: cartId,
        },
        url: '/cart/apply-coupon',
        cache: false,
        success: function (resp) {
            alert(resp.message)
        },
        error: function (error) {
            alert('Đã xảy ra lỗi!')
        },
        complete: function () {
            window.location.reload();
        }
    });
}

function checkout(cartId){
    $.ajax({
        type: "POST",
        dataType: "JSON",
        data: {
            cartId: cartId,
        },
        url: '/cart/checkout',
        cache: false,
        success: function (resp) {
            alert(resp.message)
        },
        error: function (error) {
            //alert(error.message)
        },
        complete: function () {
            window.location.reload();
        }
    });
}

document.addEventListener('DOMContentLoaded', function() {
    var sizeRadios = document.querySelectorAll('.size-radio');
    var colorRadios = document.querySelectorAll('.color-radio');
    var productId = document.getElementById('product_id');
    var priceDisplay = document.getElementById('price-display');

    sizeRadios.forEach(function(radio) {
        radio.addEventListener('change', updatePrice);
    });

    colorRadios.forEach(function(radio) {
        radio.addEventListener('change', updatePrice);
    });

    function updatePrice() {
        var selectedSizeRadio = document.querySelector('.size-radio:checked');
        var selectedSizeId = selectedSizeRadio ? selectedSizeRadio.value : null;
        
        var selectedColorRadio = document.querySelector('.color-radio:checked');
        var selectedColorId = selectedColorRadio ? selectedColorRadio.value : null;
        var productId = document.getElementById('product_id').value;

        $.ajax({
            method: 'GET',
            datatype:'JSON',
            data:{
                product_id: productId,
                size_id: selectedSizeId,
                color_id: selectedColorId 
            },
            url: '/home/detail/updatePriceDetail',
            success: function(response) {
                if(response.error === false){
                  //alert(response.price);
                    priceDisplay.innerHTML = response.price
                }
                else{
                    priceDisplay.innerHTML = '<h3 style="color:red">Hết hàng</h3>'

                }
            },
            error: function(error) {
                //alert('Đã xảy ra lỗi!');
            }
        });    
    }
    updatePrice();
});

document.addEventListener("DOMContentLoaded", function() {
    const urlParams = new URLSearchParams(window.location.search);
    const search = urlParams.get('search');
    if (search) {
        $('html, body').animate({
            scrollTop: $('#product-list').offset().top - 200
        }, 500);
    }
});

// Hàm để thêm thông báo mới
function updateNotificationCount(){
    // Cập nhật số lượng thông báo chưa xem
    let numberElement = document.querySelector('.bell .number');
    let currentCount = parseInt(numberElement.textContent, 10) || 0;
    numberElement.textContent = currentCount + 1;
}

function truncateMessage(message, maxLength) {
    if (message.length > maxLength) {
        return message.substring(0, maxLength) + '...';
    } else {
        return message;
    }
}


function addNewNotification(notification){
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


document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search');
    const searchHistory = document.getElementById('search-history');

    // Hiển thị lịch sử tìm kiếm khi ô input được focus
    searchInput.addEventListener('focus', function() {
        // Hiển thị lịch sử tìm kiếm
        renderSearchHistory();
        searchHistory.style.display = 'block';
    });

    // Ẩn lịch sử tìm kiếm khi click ra ngoài
    document.addEventListener('click', function(event) {
        if (!searchInput.contains(event.target) && !searchHistory.contains(event.target)) {
            searchHistory.style.display = 'none';
        }
    });

    // Xử lý khi click vào một mục trong lịch sử tìm kiếm
    searchHistory.addEventListener('click', function(event) {
        if (event.target.classList.contains('search-history-item')) {
            searchInput.value = event.target.textContent;
            searchInput.form.submit(); // Gửi form
        }
        setTimeout(function() {
            scrollToResults();
        }, 500); 
    });

    function scrollToResults() {
        const resultsContainer = document.getElementById('product-list');
        if (resultsContainer) {
            resultsContainer.scrollIntoView({
                behavior: 'smooth', // Thêm hiệu ứng cuộn mượt mà
                block: 'start' // Cuộn đến đầu phần tử
            });
        }
    }
});

function saveSearchKeyword(keyword) {
    const maxHistoryLength = 5; 
    let searchHistory = JSON.parse(localStorage.getItem('searchHistory')) || [];

    // Thêm từ khóa vào lịch sử nếu không có
    if (!searchHistory.includes(keyword)) {
        searchHistory.unshift(keyword); // Thêm vào đầu mảng
        // Giới hạn lịch sử tìm kiếm
        if (searchHistory.length > maxHistoryLength) {
            searchHistory.pop(); // Xóa từ khóa cũ nhất nếu quá giới hạn
        }
        localStorage.setItem('searchHistory', JSON.stringify(searchHistory));
    }
}

function renderSearchHistory() {
    const searchHistory = JSON.parse(localStorage.getItem('searchHistory')) || [];
    const searchHistoryList = document.getElementById('search-history');
    
    // Xóa tất cả các mục hiện có
    searchHistoryList.innerHTML = '';
    
    // Thêm các mục mới vào lịch sử tìm kiếm
    searchHistory.forEach(function(keyword) {
        const listItem = document.createElement('div');
        listItem.className = 'search-history-item';
        listItem.textContent = keyword;
        searchHistoryList.appendChild(listItem);
    });
}
