<?php

namespace FashionCore\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderItem extends Model
{
    use HasFactory;
    protected $fillable = [
        'quantity',
        'price',
        'total',
        'warehouse_item_id',
        'order_id',
    ];

    public function warehouse_item(){
        return $this->belongsTo(WarehouseItem::class, 'warehouse_item_id', 'id');
    }
    
    public function order(){
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }
}