<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable = [
        'delivery_id',
        'menu_name',
        'quantity'
    ];

    public function delivery()
    {
        return $this->belongsTo(Delivery::class);
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class, 'menu_name', 'name');
    }
}
