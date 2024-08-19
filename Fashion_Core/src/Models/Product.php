<?php

namespace FashionCore\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'code',
        'status',
        'description',
        'category_id',  
    ];

    public function category(){
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function images(){
        return $this->hasMany(Image::class, 'product_id', 'id');
    }

    public function warehouse_items(){
        return $this->hasMany(WarehouseItem::class, 'product_id', 'id');
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class, 'product_id');
    }
}