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
        'discount',
        'description',
        'image',
        'rating',
        'availability',
    ];

    public function cartUsers()
    {
        return $this->belongsToMany(User::class, 'cart_items');
    }

    public function favoritedByUsers()
    {
        return $this->belongsToMany(User::class, 'favorite_items');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}