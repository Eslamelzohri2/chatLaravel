<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'chat_id',
        'sender_id',
        'receiver_id',
        'message',
        'is_read',
         'reaction'
    ];

    // علاقة الرسالة بالشات
    public function chat()
    {
        return $this->belongsTo(Chat::class);
    }

    // المرسل
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    // المستقبل
    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }
}
