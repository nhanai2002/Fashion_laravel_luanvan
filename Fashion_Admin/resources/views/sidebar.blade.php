<div class="sidenav">
    <div class="sidenav-top">
        <img src="/template/asset/image/logo.png" alt="">
        <p style="margin-top: 15px">AH FASHION</p>
    </div>
    <ul id="dropdown">
        <li class="menu-item">
            <a href="{{ route('admin') }}">
                <i class="fa-solid fa-cube"></i> Tổng quan
            </a>
        </li>
        @if(hasPermission('view_report', $check_permissions))
        <li class="menu-item">
            <a href="/admin/report/index">
                <i class="fa-solid fa-chart-simple"></i> Doanh thu
            </a>
        </li>
        @endif
        @if(hasPermission('view_category', $check_permissions))
        <li class="menu-item">
            <a href="/admin/category/index">
                <i class="fa-solid fa-layer-group"></i> Phân loại
            </a>
        </li>
        @endif
        {{-- <li class="menu-item">
            <a href="/admin/product/index"  class="menu-link">
                <i class="fa-solid fa-boxes-stacked"></i> Sản phẩm
                <i class="fa-solid fa-angle-down toggle-icon" data-toggle="closed"></i>
            </a>
            <ul class="sub-menu">
                <li class="menu-item">
                    <a href="/admin/color/index">
                        <i class="fa-solid fa-palette"></i> Màu sắc
                    </a>
                </li>
                <li class="menu-item">
                    <a href="/admin/size/index">
                        <i class="fa-solid fa-pen-ruler"></i> Kích thước
                    </a>
                </li>
            </ul>
        </li> --}}
        @if(hasPermission('view_product', $check_permissions))
        <li class="menu-item">
            <a href="/admin/product/index"  class="menu-link">
                <i class="fa-solid fa-boxes-stacked"></i> Sản phẩm
            </a>
        </li>
        @endif
        @if(hasPermission('view_coupon', $check_permissions))
        <li class="menu-item">
            <a href="/admin/coupon/index">
                <i class="fa-solid fa-gift"></i> Khuyến mãi
            </a>
        </li>
        @endif
        @if(hasPermission('view_warehouse', $check_permissions))
        <li class="menu-item">
            <a href="/admin/warehouse-item/index">
                <i class="fa-solid fa-warehouse"></i> Kho
            </a>
        </li>
        @endif
        @if(hasPermission('view_goods_receipt', $check_permissions))
        <li class="menu-item">
            <a href="/admin/goods-receipt/index">
                <i class="fa-solid fa-receipt"></i> Phiếu nhập
            </a>
        </li>
        @endif
        @if(hasPermission('view_order', $check_permissions))
        <li class="menu-item">
            <a href="/admin/order/index">
                <i class="fa-solid fa-file-invoice"></i> Đơn hàng
            </a>
        </li>
        @endif
        @if(hasPermission('view_user', $check_permissions))
        <li class="menu-item">
            <a href="/admin/customer/index">
                <i class="fa-solid fa-users"></i> Người dùng
            </a>
        </li>
        @endif
        @if(hasPermission('view_role', $check_permissions))
        <li class="menu-item">
            <a href="/admin/role/index">
                <i class="fa-solid fa-user-tag"></i> Vai trò
            </a>
        </li>
        @endif
        {{-- @if(hasPermission('view_role', $check_permissions)) --}}
        <li class="menu-item">
            <a href="/admin/notification/index">
                <i class="fa-solid fa-bell"></i> Thông báo
            </a>
        </li>
        {{-- @endif --}}
        @if(hasPermission('view_color', $check_permissions))
        <li class="menu-item">
            <a href="/admin/color/index">
                <i class="fa-solid fa-palette"></i> Màu sắc
            </a>
        </li>
        @endif
        @if(hasPermission('view_size', $check_permissions))
        <li class="menu-item">
            <a href="/admin/size/index">
                <i class="fa-solid fa-pen-ruler"></i> Kích thước
            </a>
        </li>
        @endif
        {{-- <li class="menu-item">
            <a href="#">
                <i class="fa-solid fa-briefcase"></i> Administrator
            </a>
        </li> --}}
    </ul>
</div>
