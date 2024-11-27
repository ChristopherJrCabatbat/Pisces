<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_id', 
        'sender_role', 
        'message_text', 
        'is_read',
    ];

    // Relationship to the User model
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
