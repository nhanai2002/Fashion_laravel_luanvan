<?php

namespace FashionCore\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use PHPUnit\Framework\Constraint\Count;

class Cart extends Model
{
    use HasFactory;
    protected $fillable = [
        'total',
        'user_id',
        'coupon_id'
    ];

    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    
    public function coupon(){
        return $this->belongsTo(Coupon::class, 'coupon_id', 'id');
    }

    public function cart_items(){
        return $this->hasMany(CartItem::class, 'cart_id', 'id');
    }
}