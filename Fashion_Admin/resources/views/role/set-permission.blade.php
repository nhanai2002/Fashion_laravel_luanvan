@extends('main')

@section('head')
<style>
  .permissions-container {
      display: flex;
      flex-wrap: wrap;
  }
  .permissions-column {
      width: 50%;
      box-sizing: border-box;
      padding: 10px;
      border: 1px solid #e9e9e9;
      box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
      margin-bottom: 5px; /* Thêm khoảng cách giữa các cột */
      background-color: #fff;
  }
</style>
@endsection


@section('content')
<h4 class="form__title">Phân quyền chức năng</h4>

<div class="form-group">
    <h5>Tên vai trò</h5>
    <input type="text" class="form-control" value="{{ $role }}" readonly>
</div>

<br/>

<form action="" method="post" role="form" >
    @csrf
    @php
      $groupedPermissions = $permissions->groupBy('permission_group');
    @endphp

    <div class="permissions-container">
        @foreach($groupedPermissions as $group => $perms)
        <div class="permissions-column">
            <h5>{{ $group }}</h5>
            <ul>
                @foreach($perms as $permission)
                    <li>
                        <label>
                            <input type="checkbox" name="permissionIds[]" value="{{ $permission->id }}" 
                                {{ $hasPermission->contains($permission->key) ? 'checked' : '' }}>
                            {{ $permission->name }}
                        </label>
                    </li>
                @endforeach
            </ul>
        </div>
      @endforeach

      </div>


      <div class="box-footer">
        <button type="submit" class="btn btn-success">Phân quyền</button>
      </div>
    </div>
  </form>
@endsection

@section('footer')
<script>

</script>
@endsection