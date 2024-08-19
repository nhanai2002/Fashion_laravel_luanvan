<?php

namespace FashionCore\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Image extends Model
{
    use HasFactory;
    protected $fillable = [
        'url',
        'product_id',
    ];

    public function product(){
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}