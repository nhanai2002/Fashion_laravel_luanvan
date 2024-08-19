@extends('main')
@section('content')

<div class="account__text-header">
    <p>
        <a href="{{ Route('home.index') }}">Trang chủ
        <i class="fa-solid fa-angles-right"></i>
        </a> 
        Quản lý tài khoản
    </p>
</div>
<div class="order-address">
    <h2 class="order-header"> Quản lý tài khoản</h2>
</div>
<div class="row account_background">
    <div class="col-md-4">
        <div class="info-admin">
            <img src="{{ $profile->avatar }}" id="avatarImage">
            <label class="btn btn-warning" for="avatarInput" style="margin-top: 20px"> Đổi ảnh đại diện
                <input type="file" name="avatar" id="avatarInput" style="display: none;" accept="image/*" onchange="previewAvatar(event)">
            </label>
            <div id="updateSection" style="display: none; margin-top: 20px;">
                <button class="btn btn-success" onclick="updateAvatar()">Cập nhật</button>
            </div>
            <div class="name">
                <p>{{ $profile->name }}</p>
            </div>
            <div class="role">
                <p>{{ $role_text }}</p>
            </div>
        </div>
    </div>
    <form action="/account/update" method="post" enctype="multipart/form-data" class="col-md-6">
        @csrf
        <div>
            <div class="tab-account__header">
                <span class="tab tab-account__active" data-target="info">Thông tin</span>
                <span class="tab " data-target="account">Tài khoản</span>
            </div>

            
            <div  id="info-content" class="tab-content active">
                <table>
                  <tr>
                      <th>Họ tên</th>
                      <td><input type="text" name="name" value="{{ $profile->name }}"></td>
                  </tr>
                  <tr>
                      <th>Email</th>
                      <td><input type="email" name="email" value="{{ $profile->email }}"></td>
                  </tr>
                  <tr>
                      <th>Số điện thoại</th>
                      <td><input type="number" name="phone" value="{{ $profile->phone }}"></td>
                  </tr>
                  <tr>
                      <th>Địa chỉ</th>
                      <td><input type="text" name="address" value="{{ $profile->address }}"></td>
                  </tr>
                  <tr>
                      <th>Ngày sinh</th>
                      <td><input type="date" name="birthday" value="{{ $profile->birthday }}"></td>
                  </tr>
                  <tr>
                      <td colspan="2">
                          <button type="submit" name="btn_save" value="0">
                              Cập nhật
                          </button>
                      </td>
                  </tr>
                </table>
            </div>

            <div id="account-content" class="tab-content">
              <table>
                <tr>
                    <th>Tài khoản</th>
                    <td><input type="text" name="username" value="{{ $profile->username }}" readonly></td>
                </tr>
                <tr>
                    <th>Mật khẩu</th>
                    <td><input type="password" name="password" placeholder="Nhập mật khẩu cũ"></td>
                </tr>
                <tr>
                    <th>Mật khẩu mới</th>
                    <td><input type="password" name="password_new" placeholder="Nhập mật khẩu mới"></td>
                </tr>
                <tr>
                  <th>Nhập lại mật khẩu mới</th>
                  <td><input type="password" name="password_new_confirmation" placeholder="Nhập lại mật khẩu mới"></td>
              </tr>
                
                <tr>
                    <td colspan="2">
                        <button type="submit" name="btn_save" value="1">
                            Cập nhật
                        </button>
                    </td>
                </tr>
              </table>
            </div>
        </div>
    </form>
</div>

<script>
    function previewAvatar(event) {
        var file = event.target.files[0]; // Lấy file đầu tiên được chọn
        var reader = new FileReader();

        reader.onload = function(e) {
            document.getElementById('avatarImage').src = e.target.result; // Hiển thị hình ảnh mới lên thẻ img
        };

        reader.readAsDataURL(file); // Đọc file dưới dạng Data URL
        document.getElementById('updateSection').style.display = 'block';
    }

    function updateAvatar() {
        var file = document.getElementById('avatarInput').files[0];
        var formData = new FormData();
        formData.append('avatar', file);

        $.ajax({
            url: '{{ route("account.change.avatar") }}', 
            type: 'POST',
            data: formData,
            contentType: false, 
            processData: false,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            success: function (response) {
                if(response.error == false){
                    alert('Đổi ảnh đại diện thành công');
                        document.getElementById('changeAvatarLabel').style.display = 'block'; // Hiển thị lại nút đổi ảnh
                        document.getElementById('updateSection').style.display = 'none'; // Ẩn nút cập nhật
                    }
                },
            error: function (xhr) {
                alert('Lỗi khi cập nhật ảnh đại diện');
            }
        });
    }



    document.addEventListener('DOMContentLoaded', function() {
        var tabs = document.querySelectorAll('.tab');
        var contents = document.querySelectorAll('.tab-content');

        // Thiết lập tab "Thông tin" là active khi trang tải lần đầu
        document.querySelector('.tab[data-target="info"]').classList.add('tab-account__active');
        
        tabs.forEach(function(tab) {
            tab.addEventListener('click', function() {
                var target = tab.getAttribute('data-target');
                
                // Loại bỏ lớp active khỏi tất cả các tab và nội dung
                tabs.forEach(function(item) {
                    item.classList.remove('tab-account__active');
                });
                contents.forEach(function(content) {
                    content.classList.remove('active');
                });
                
                // Thêm lớp active vào tab và nội dung được chọn
                tab.classList.add('tab-account__active');
                document.getElementById(target + '-content').classList.add('active');
            });
        });
    });


</script>
@endsection