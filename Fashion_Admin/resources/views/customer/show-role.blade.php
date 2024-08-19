@extends('main')

@section('content')
<h4 class="form__title">Phân quyền cho người dùng</h4>

<form action="" method="post" role="form" >
  @csrf

    <div class="form-group">
        <label>Tài khoản</label>
        <input type="text" value="{{ $user->username }}" class="form-control" readonly>
    </div>
      <div class="form-group">
        <label>Phân quyền</label>
        <select name="role_id" class="form-select">
            @foreach($roles as $role)
              <option value="{{$role->id}}" {{ $user->role_id == $role->id ? 'selected' :'' }} >{{$role->name}}</option>
            @endforeach
        </select>
      </div>

      <div class="box-footer">
        <button type="submit" class="btn btn-success">Phân quyền</button>
      </div>
    </div>
  </form>
@endsection

