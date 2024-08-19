<?php

namespace FashionCore\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Coupon extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'code',
        'status',
        'description',
        'quantity',     // số lượng còn lại
        'value',
        'type',         // kiểu coupon
        'time_start',
        'time_end'
    ];
}