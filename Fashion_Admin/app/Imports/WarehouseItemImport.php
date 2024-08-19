<?php

namespace App\Imports;

use Carbon\Carbon;
use FashionCore\Models\Size;
use FashionCore\Models\Color;
use FashionCore\Models\Product;
use Illuminate\Support\Collection;
use FashionCore\Models\GoodsReceipt;
use FashionCore\Models\GoodsReceiptDetail;
use Illuminate\Support\Facades\Auth;
use FashionCore\Models\WarehouseItem;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

// trả từng dòng
// class WarehouseItemImport implements ToModel, WithHeadingRow
// {
//     use Importable;
//     public function model(array $row)
//     {
//         $product_code = trim($row['ma_san_pham']);
//         $product = Product::where('code', $product_code)->first();
//         if (!$product) {
//             Session::flash('error',"Mã sản phẩm {$row['ma_san_pham']} không tồn tại!");
//             return null; 
//         }
//         $color = Color::where('id', $row['ma_mau'])->first();
//         if(!$color) {
//             Session::flash('error',"Mã màu {$row['ma_mau']} không tồn tại!");
//             return null; 
//         }
//         $size = Size::where('id', $row['ma_kich_thuoc'])->first();
//         if(!$size) {
//             Session::flash('error',"Mã kích thước {$row['ma_kich_thuoc']} không tồn tại!");
//             return null; 
//         }
//         if($row['so_luong'] == null || $row['so_luong'] == '') {
//             Session::flash('error',"Số lượng không được rỗng!");
//             return null; 
//         }

//         if($row['gia_goc'] == null || $row['gia_goc'] == '') {
//             Session::flash('error',"Giá gốc không được rỗng!");
//             return null; 
//         }
//         $check = WarehouseItem::where([
//             'product_id' => $row['ma_san_pham'],
//             'color_id' => $row['ma_mau'],
//             'size_id' => $row['ma_kich_thuoc'],
//         ])->first();
//         if($check){

//             return $check;
//         }
//         return new WarehouseItem([
//             'product_id' => $product->id,
//             'color_id' => $color->id,
//             'size_id' => $size->id,
//             'quantity' => $row['so_luong'],
//             'base_price' => $row['gia_goc'],
//         ]);
//     }
// }

class WarehouseItemImport implements ToCollection, WithHeadingRow
{
    use Importable;

    protected $receipt;

    public function __construct()
    {
        $this->receipt = GoodsReceipt::create([
            'code' => 'GR_' . Carbon::now()->format('YmdHis'),
            'input_day' => Carbon::now(),
            'total' => 0,
            'user_id' => Auth::id()
        ]);
    }

    public function collection(Collection $rows)
    {
        $total = 0;
        foreach ($rows as $row){
            $product_code = trim($row['ma_san_pham']);
            $product = Product::where('code', $product_code)->first();
            if (!$product) {
                //Session::flash('error',"Mã sản phẩm {$row['ma_san_pham']} không tồn tại!");
                return null; 
            }
            $color = Color::where('id', $row['ma_mau'])->first();
            if(!$color) {
                Session::flash('error',"Mã màu {$row['ma_mau']} không tồn tại!");
                return null; 
            }
            $size = Size::where('id', $row['ma_kich_thuoc'])->first();
            if(!$size) {
                Session::flash('error',"Mã kích thước {$row['ma_kich_thuoc']} không tồn tại!");
                return null; 
            }
            if($row['so_luong'] == null || $row['so_luong'] == '') {
                Session::flash('error',"Số lượng không được rỗng!");
                return null; 
            }
            if($row['gia_goc'] == null || $row['gia_goc'] == '') {
                Session::flash('error',"Giá gốc không được rỗng!");
                return null; 
            }


            $check = WarehouseItem::where([
                'product_id' => $product->id,
                'color_id' => $row['ma_mau'],
                'size_id' => $row['ma_kich_thuoc'],
            ])->first();
            if($check){
                $check->quantity += $row['so_luong'];
                $check->save();

                // chi tiết phiếu nhập
                GoodsReceiptDetail::create([
                    'base_price' => $row['gia_goc'],
                    'quantity' => $row['so_luong'],
                    'warehouse_item_id' => $check->id,
                    'goods_receipt_id' => $this->receipt->id,
                ]);
            }
            else{
                $newItem = new WarehouseItem([
                    'product_id' => $product->id,
                    'color_id' => $row['ma_mau'],
                    'size_id' => $row['ma_kich_thuoc'],
                    'quantity' => $row['so_luong'],
                    'base_price' => $row['gia_goc'],
                ]);
                $newItem->save();
                GoodsReceiptDetail::create([
                    'base_price' => $row['gia_goc'],
                    'quantity' => $row['so_luong'],
                    'warehouse_item_id' => $newItem->id,
                    'goods_receipt_id' => $this->receipt->id,
                ]);
            }
            $total += $row['so_luong']*$row['gia_goc'];
            $this->receipt->total = $total;
            $this->receipt->save();

        }
    }
}

