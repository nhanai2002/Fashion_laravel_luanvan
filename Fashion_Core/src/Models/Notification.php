<?php

namespace FashionCore\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Notification extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'message',
        'seen',
        'type',
        'date_received'     // ngày nhận (khi gửi thì mới set)
    ];


    public function users()
    {
        return $this->belongsToMany(User::class, 'notification_users');
    }

}