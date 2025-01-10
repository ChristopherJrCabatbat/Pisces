<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Delivery extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'contact_number',
        'order',
        'address',
        'quantity',
        'shipping_method',
        'shipping_fee',
        'mode_of_payment',
        'note',
        'status',
        'rider',
        'total_price',
    ];

    // Relationship with rider
    public function rider()
    {
        return $this->belongsTo(Rider::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
