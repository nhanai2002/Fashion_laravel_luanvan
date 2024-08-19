<?php

namespace FashionCore\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GoodsReceiptDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        'base_price',
        'quantity',
        'warehouse_item_id',
        'goods_receipt_id',
    ];

    public function warehouse_item(){
        return $this->belongsTo(WarehouseItem::class, 'warehouse_item_id', 'id');
    }
    public function goods_receipt(){
        return $this->belongsTo(GoodsReceipt::class, 'goods_receipt_id', 'id');
    }
}