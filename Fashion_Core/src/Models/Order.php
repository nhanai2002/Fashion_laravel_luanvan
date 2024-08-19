<?php

namespace FashionCore\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;
    protected $fillable = [
        'code',
        'order_status',
        'payment_status',
        'note',
        'address',
        'user_id',
        'total',
        'order_day'
    ];

    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function order_items(){
        return $this->hasMany(OrderItem::class, 'order_id', 'id');
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class, 'order_id'); 
    }
}