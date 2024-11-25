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
        'mode_of_payment',
        'note',
        'status',
        'rider',
    ];

    // Relationship with rider
    public function rider()
    {
        return $this->belongsTo(Rider::class);
    }
}