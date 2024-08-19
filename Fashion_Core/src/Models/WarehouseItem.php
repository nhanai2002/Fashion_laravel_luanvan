<?php

namespace FashionCore\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WarehouseItem extends Model
{
    use HasFactory;
    protected $fillable = [
        //'status',
        'quantity',
        'sell_price',
        'sale_price',
        'product_id',
        'size_id',
        'color_id',        
    ];

    public function product(){
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function size(){
        return $this->belongsTo(Size::class, 'size_id', 'id');
    }

    public function color(){
        return $this->belongsTo(Color::class, 'color_id', 'id');
    }
}