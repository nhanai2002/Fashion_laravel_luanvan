<?php

namespace FashionCore\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Message extends Model
{
    use HasFactory;
    protected $fillable = [
        'content',
        'conversation_id',
        'sender_id',
    ];

    public function user(){
        return $this->belongsTo(User::class, 'sender_id', 'id');
    }

}