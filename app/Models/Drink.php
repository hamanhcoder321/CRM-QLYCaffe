<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Drink extends Model
{
    protected $fillable = [
        'name',
        'price',
        'image',
        'status',
    ];

    public function recipes()
    {
        return $this->hasMany(Recipe::class, 'drink_id');
    }
}
