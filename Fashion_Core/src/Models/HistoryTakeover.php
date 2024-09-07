<?php

namespace FashionCore\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class HistoryTakeover extends Model
{
    use HasFactory;
    protected $fillable = [
        'conversation_id',
        'take_over_by',
        'started_at',
        'stopped_at',
    ];

    public function user(){
        return $this->belongsTo(User::class, 'take_over_by', 'id');
    }

    public function conversation(){
        return $this->belongsTo(Conversation::class, 'conversation_id', 'id');
    }

}