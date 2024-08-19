<?php

namespace FashionCore\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CartItem extends Model
{
    use HasFactory;
    protected $table = 'cart_items';
    protected $fillable = [
        'quantity',
        'cart_id',
        'warehouse_item_id'
    ];

    public function cart(){
        return $this->belongsTo(Cart::class, 'cart_id', 'id');
    }

    public function warehouse_item(){
        return $this->belongsTo(WarehouseItem::class, 'warehouse_item_id', 'id');
    }
}