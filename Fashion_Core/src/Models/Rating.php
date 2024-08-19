<?php

namespace FashionCore\Models;
use Carbon\Carbon;   
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
    
class Rating extends Model{
    public $timestamps = false;

    protected $dates = ['date'];
    protected $fillable = [
        'rating_id',
        'order_id',
        'user_id',
        'product_id',
        'rating',
        'comment',
        'date'
    ];
    
    public function user(){
        return $this->belongsTo(User::class);
    }

    public function product(){
        return $this->belongsTo(Product::class);
    }
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');  
    }
}