<?php

namespace FashionCore\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Conversation extends Model
{
    use HasFactory;
    protected $fillable = [
        'is_taken_over',
        'user_id',
        'is_welcomed',
    ];

    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function messages(){
        return $this->hasMany(Message::class, 'conversation_id', 'id');
    }

    public function history_takeovers(){
        return $this->hasMany(HistoryTakeover::class, 'conversation_id', 'id');
    }
}