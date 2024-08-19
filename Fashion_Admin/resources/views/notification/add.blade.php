@extends('main')

@section('head')
    <script src="/ckeditor5/ckeditor.js"></script>
@endsection

@section('content')
<h4 class="form__title">Tạo thông báo mới</h4>

<form action="/admin/notification/add" method="post" role="form" enctype="multipart/form-data">
  @csrf
    <div class="box-body">

        <div class="form-group">
            <label>Tiêu đề</label>
            <input type="text" name="title" value="{{ old('title') }}" class="form-control" required>
        </div>
        <br>
        <label for="type" style="margin-right: 30px">Loại thông báo:</label>
        <select id="type" name="type" style="width:200px">
            <option value="0">Gửi đến tất cả</option>
            <option value="1">Gửi đến nhóm quyền</option>
            <option value="2">Gửi đến cá nhân</option>
        </select>
        <br>
        <div id="roleGroup" style="display: none;">
            <label for="role">Chọn nhóm quyền:</label>
            <select id="role" name="role_ids[]" class="js-example-basic-multiple" multiple="multiple" style="width:400px">
                @foreach($roles as $role)
                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                @endforeach
            </select>
        </div>

        <div id="userGroup" style="display: none;" >
            <label for="user">Chọn người dùng:</label>
            <select id="user" name="user_ids[]" class="js-example-basic-multiple" multiple="multiple"  style="width:400px">
                <!-- Các tùy chọn người dùng sẽ được nạp ở đây -->
                @foreach($users as $user)
                    <option value="{{ $user->id }}">{{ $user->username .' - '.$user->name }}</option>
                @endforeach
            </select>
        </div>
    
        <br>
      <div class="form-group">
        <label>Nội dung</label>
        <textarea name="message" id="content" class="form-control"></textarea>
      </div>

      <div class="box-footer">
        <button type="submit" class="btn btn-success">Tạo mới</button>
      </div>
    </div>
  </form>
@endsection

@section('footer')
<script>
    ClassicEditor
        .create( document.querySelector( '#content' ) )
        .catch( error => {
            console.error( error );
        } );


document.getElementById('type').addEventListener('change', function() {
    var type = this.value;
    var roleGroup = document.getElementById('roleGroup');
    var userGroup = document.getElementById('userGroup');

    // Ẩn tất cả các nhóm tùy chọn trước
    roleGroup.style.display = 'none';
    userGroup.style.display = 'none';

    // Hiển thị nhóm tùy chọn phù hợp dựa trên loại thông báo
    if (type == 1) {
        roleGroup.style.display = 'block';
    } else if (type == 2) {
        userGroup.style.display = 'block';
    }
});

$(document).ready(function() {
    $('.js-example-basic-multiple').select2();
});
</script>
@endsection