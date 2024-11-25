<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rider extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'rating'];

    // Relationship with deliveries
    public function deliveries()
    {
        return $this->hasMany(Delivery::class);
    }

}
