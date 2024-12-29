<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'contact_number',
        'role',
        'email',
        'password',
        'cart_count',
        'favorites_count',
        'last_login_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function cartItems()
    {
        return $this->belongsToMany(Menu::class, 'cart_items');
    }

    public function favoriteItems()
    {
        return $this->belongsToMany(Menu::class, 'favorite_items');
    }

    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'user_id'); // 'user_id' is the sender
    }

    // Relationship for messages received by the user
    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'receiver_id'); // 'receiver_id' is the recipient
    }

    // Check if the user qualifies for a "We Miss You" discount
    public function checkInactivityDiscount(): bool
    {
        if (!$this->last_login_at) {
            return false; // No previous login record
        }

        // Calculate the difference in days since the last login
        return now()->diffInDays($this->last_login_at) > 5;
    }
}
