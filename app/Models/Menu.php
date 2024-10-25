<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category',
        'price',
        'description',
        'image',
    ];

    public function cartUsers()
    {
        return $this->belongsToMany(User::class, 'cart_items');
    }
}
