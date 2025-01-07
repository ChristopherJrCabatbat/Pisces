<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_name',
        'order_number',
        'menu_items',
        'feedback_text',
        'rating',
        'rider_rating',
        'sentiment',
        'response',
    ];
}
