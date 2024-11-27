<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'receiver_id',
        'sender_role',
        'message_text',
        'is_read',
    ];

    // Relationship to the User model (sender)
    public function sender()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relationship to the User model (receiver)
    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }
}
