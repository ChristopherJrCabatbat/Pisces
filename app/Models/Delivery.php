<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Delivery extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 
        'contact_number', 
        'order',
        'address',
        'quantity',
        'mode_of_payment',
        'status'
    ];
}
