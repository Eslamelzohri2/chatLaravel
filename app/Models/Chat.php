<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory;

    protected $fillable = ['user1_id', 'user2_id'];

    // العلاقة مع أول مستخدم (المرسل أو صاحب المحادثة)
    public function user1()
    {
        return $this->belongsTo(User::class, 'user1_id');
    }

    // العلاقة مع المستخدم الثاني (المستلم)
    public function user2()
    {
        return $this->belongsTo(User::class, 'user2_id');
    }

    // العلاقة مع الرسائل
    public function messages()
    {
        return $this->hasMany(Message::class);
    }
    
}
