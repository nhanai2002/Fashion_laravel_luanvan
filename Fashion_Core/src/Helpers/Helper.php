<?php

// toàn cục nên không cần namespace
//namespace FashionCore\Helpers;

use Illuminate\Support\Str;
use FashionCore\Models\User;
use FashionCore\Models\Coupon;

    // để nhận diện đc file này và không cần phải gọi từ đường dẫn ra nên t sẽ khai báo trong 
    // composer.json phần autoload

    function categoryView($categories, $check_permissions, $parent_id = 0, $char=''){
        $html = '';
        
        foreach($categories as $key => $item){
            if($item->parent_id == $parent_id){
                $html .= '
                <tr>
                    <td>' .$item->id        .'</td>
                    <td>' . $char .$item->name      .'</td>
                    <td>' .$item->created_at.'</td>
                    <td>' ;
                        if(hasPermission("edit_category", $check_permissions)) {
                            $html .= '<a class="btn btn-primary btn-sm" href="/admin/category/edit/'.$item->id.'">
                            <i class="fa-solid fa-pen-to-square"></i>
                            </a>';
                        }
                        if(hasPermission("delete_category", $check_permissions)){
                            $html .= '<a href="#" onclick="removeRow('.$item->id.', \'/admin/category/destroy\')" class="btn btn-danger btn-sm">
                                <i class="fa-solid fa-trash"></i>
                            </a>';
                        }
                $html .= '</td>  
                </tr> ';

                // de quy
                unset($categories[$key]);
                $html .= categoryView($categories, $check_permissions, $item->id, $char .'|-- ');

            }
        }
        return $html;
    }

    function active($active = 0) : string
    {
        return $active == 0 ? '<span class="btn btn-danger btn-sm">NO</span>' : '<span class="btn btn-success btn-sm">YES</span>';
    }

    function isChild($categories, $id = 0) : bool
    {
        foreach($categories as $item){
            if($item->parent_id == $id){
                return true;
            }
        }
        return false;
    }

    function listCategoryView($categories, $parent_id = 0) : string
    {
        $html = '';
        foreach($categories as $key => $item){
            if($item->parent_id == $parent_id){
                if(isChild($categories, $item->id)){
                    $html .= '
                    <div class="nav-item dropdown">
                        <a href="/category/'. $item->id . '-'.Str::slug($item->name, '-').'.html" class="nav-link" data-toggle="dropdown">
                        '.$item->name.'
                        <i class="fa fa-angle-down float-right mt-1"></i>
                        </a>
                ';
                    $html .= '<div class="dropdown-menu-child">';
                    $html .= '
                        <a class="dropdown-item">
                            '.listCategoryView($categories, $item->id).'
                        </a>
                        </div>
                    </div>';

                }
                else{
                    $html .= '
                    <a href="/category/'. $item->id . '-'.Str::slug($item->name, '-').'.html" class="nav-item nav-link">
                    '.$item->name.'
                    </a>
                ';
                }
                unset($categories[$key]);
            }
        }
        return $html;
    }

    function price($sell_price = 0, $sale_price = 0, $quantity = 0): string
    {
        $html = '';
        if($quantity > 0){
            $html .= '<div>Kho: '. $quantity.'</div>';
            $html .= '<input type="hidden" id="max_quantity" value="' . $quantity . '">';
        }
        if($sell_price != 0 && $sale_price != 0){
            $html .= '<h3 style="padding-right:8px">'.number_format($sale_price) .'đ </h3>';
            $html .= '<h4 class="text-muted ml-2"><del>'.number_format($sell_price).'đ</del> </h4>';
        } 
        else if($sell_price != 0 && $sale_price == 0){
            $html .= '<h3>'.number_format($sell_price).'đ </h3>';
        }
        else{
            $html .= '<a href="/lien-he.hml" style="color:red;"><h3>Liên hệ</h3></a>';
        }

        return $html;
    }

    function getDisplayStatusOrder($status){
        switch($status){
            case 0: return 'Đã hủy';
            case 1: return 'Chờ xác nhận';
            case 2: return 'Đang xử lý';
            case 3: return 'Đang giao';
            case 4: return 'Hoàn thành';
        }   
    }

    function getDisplayStatusPayment($status){
        switch($status){
            case 0: return 'Thanh toán trực tiếp';
            case 1: return 'Đã thanh toán';
        }   
    }

    function checkUsedCoupon($couponId){
        $coupon = Coupon::find($couponId);
        if($coupon){
            if($coupon->type == 0){
                return '<p>Áp dụng coupon</p>  <p>'. number_format($coupon->value ?? 0) .' đ</p>';
            }
            else{
                return '<p>Áp dụng coupon</p> <p>'. number_format($coupon->value ?? 0) .'%</p>';
            }
        }
        else{
            return '';
        }
    }

    function hasPermission($key, $check_permissions){
        return in_array('*', $check_permissions) || in_array($key, $check_permissions);
    }

    function getRole($userId){
        $user = User::where('id', $userId)->with('role')->first();
        if($user){
            return $user->role;
        }
        return null;
    }