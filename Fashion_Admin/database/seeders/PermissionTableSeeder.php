<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use FashionCore\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PermissionTableSeeder extends Seeder
{

    public function run(): void
    {
        $permissions = [
            // ['key' => 'view_dashboard', 'name' => 'Xem tổng quan', 'permission_group' => 'Tổng quan'],
            
            ['key' => 'view_report', 'name' => 'Xem báo cáo doanh thu', 'permission_group' => 'Quản lý doanh thu'],
            ['key' => 'export_report', 'name' => 'Xuất excel báo cáo', 'permission_group' => 'Quản lý doanh thu'],

            ['key' => 'view_user', 'name' => 'Xem người dùng', 'permission_group' => 'Quản lý người dùng'],
            ['key' => 'create_users', 'name' => 'Tạo người dùng', 'permission_group' => 'Quản lý người dùng'],
            ['key' => 'edit_users', 'name' => 'Sửa người dùng', 'permission_group' => 'Quản lý người dùng'],
            ['key' => 'set_role', 'name' => 'Phân quyền người dùng', 'permission_group' => 'Quản lý người dùng'],


            ['key' => 'view_product', 'name' => 'Xem danh sách sản phẩm', 'permission_group' => 'Quản lý sản phẩm'],
            ['key' => 'create_product', 'name' => 'Tạo sản phẩm', 'permission_group' => 'Quản lý sản phẩm'],
            ['key' => 'delete_product', 'name' => 'Xóa sản phẩm', 'permission_group' => 'Quản lý sản phẩm'],
            ['key' => 'edit_product', 'name' => 'Sửa sản phẩm', 'permission_group' => 'Quản lý sản phẩm'],
            ['key' => 'change_status_product', 'name' => 'Thay đổi trạng thái sản phẩm', 'permission_group' => 'Quản lý sản phẩm'],
        
        
            ['key' => 'view_category', 'name' => 'Xem danh sách phân loại', 'permission_group' => 'Quản lý phân loại'],
            ['key' => 'create_category', 'name' => 'Thêm phân loại', 'permission_group' => 'Quản lý phân loại'],
            ['key' => 'delete_category', 'name' => 'Xóa phân loại', 'permission_group' => 'Quản lý phân loại'],
            ['key' => 'edit_category', 'name' => 'Sửa phân loại', 'permission_group' => 'Quản lý phân loại'],

        
            ['key' => 'view_goods_receipt', 'name' => 'Xem danh sách phiếu nhập', 'permission_group' => 'Quản lý phiếu nhập'],
            ['key' => 'delete_goods_receipt', 'name' => 'Xóa phiếu nhập', 'permission_group' => 'Quản lý phiếu nhập'],
            ['key' => 'detail_goods_receipt', 'name' => 'Xem chi tiết phiếu nhập', 'permission_group' => 'Quản lý phiếu nhập'],
            ['key' => 'export_goods_receipt', 'name' => 'Xuất phiếu nhập', 'permission_group' => 'Quản lý phiếu nhập'],


            ['key' => 'view_warehouse', 'name' => 'Xem danh sách sản phẩm đã nhập kho', 'permission_group' => 'Quản lý kho'],
            ['key' => 'create_warehouse', 'name' => 'Nhập kho', 'permission_group' => 'Quản lý kho'],
            //['key' => 'delete_warehouse', 'name' => 'Xóa sản phẩm trong kho', 'permission_group' => 'Quản lý kho'],
            ['key' => 'edit_warehouse', 'name' => 'Cập nhật giá sản phẩm', 'permission_group' => 'Quản lý kho'],
            ['key' => 'export_warehouse', 'name' => 'Xuất excel', 'permission_group' => 'Quản lý kho'],
            ['key' => 'import_warehouse', 'name' => 'Nhập excel', 'permission_group' => 'Quản lý kho'],


            ['key' => 'view_coupon', 'name' => 'Xem danh sách mã khuyến mãi', 'permission_group' => 'Quản lý mã khuyến mãi'],
            ['key' => 'create_coupon', 'name' => 'Tạo mã khuyến mãi', 'permission_group' => 'Quản lý mã khuyến mãi'],
            ['key' => 'delete_coupon', 'name' => 'Xóa mã khuyến mãi', 'permission_group' => 'Quản lý mã khuyến mãi'],
            ['key' => 'edit_coupon', 'name' => 'Sửa mã khuyến mãi', 'permission_group' => 'Quản lý mã khuyến mãi'],


            ['key' => 'view_color', 'name' => 'Xem danh sách màu sắc', 'permission_group' => 'Quản lý màu sắc'],
            ['key' => 'create_color', 'name' => 'Tạo màu sắc', 'permission_group' => 'Quản lý màu sắc'],
            ['key' => 'delete_color', 'name' => 'Xóa màu sắc', 'permission_group' => 'Quản lý màu sắc'],
            ['key' => 'edit_color', 'name' => 'Sửa màu sắc', 'permission_group' => 'Quản lý màu sắc'],


            ['key' => 'view_size', 'name' => 'Xem danh sách kích thước', 'permission_group' => 'Quản lý kích thước'],
            ['key' => 'create_size', 'name' => 'Tạo kích thước', 'permission_group' => 'Quản lý kích thước'],
            ['key' => 'delete_size', 'name' => 'Xóa kích thước', 'permission_group' => 'Quản lý kích thước'],
            ['key' => 'edit_size', 'name' => 'Sửa kích thước', 'permission_group' => 'Quản lý kích thước'],


            ['key' => 'view_order', 'name' => 'Xem danh sách đơn hàng', 'permission_group' => 'Quản lý đơn hàng'],
            ['key' => 'edit_order', 'name' => 'Sửa đơn hàng', 'permission_group' => 'Quản lý đơn hàng'],
            ['key' => 'export_order', 'name' => 'Xuất hóa đơn', 'permission_group' => 'Quản lý đơn hàng'],


            ['key' => 'view_role', 'name' => 'Xem danh sách vai trò', 'permission_group' => 'Quản lý vai trò'],
            ['key' => 'create_role', 'name' => 'Tạo vai trò', 'permission_group' => 'Quản lý vai trò'],
            ['key' => 'delete_role', 'name' => 'Xóa vai trò', 'permission_group' => 'Quản lý vai trò'],
            ['key' => 'edit_role', 'name' => 'Sửa vai trò', 'permission_group' => 'Quản lý vai trò'],
            ['key' => 'set_permission', 'name' => 'Phân quyền chức năng', 'permission_group' => 'Quản lý vai trò'],


        
        ];

        foreach ($permissions as $permission) {
            Permission::updateOrCreate(
                ['key' => $permission['key']],
                $permission
            );
        }
    }
}
