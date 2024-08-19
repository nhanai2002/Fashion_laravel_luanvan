<?php

namespace FashionCore\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class NotificationUser extends Model
{
    use HasFactory;
    protected $fillable = [
        'notification_id',
        'user_id',
    ];


    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function notification(){
        return $this->belongsTo(Notification::class, 'notification_id', 'id');
    }
}