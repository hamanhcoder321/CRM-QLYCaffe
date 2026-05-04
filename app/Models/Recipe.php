<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    protected $fillable = [
        'drink_id',
        'product_id',
        'quantity',
    ];

    public function drink()
    {
        return $this->belongsTo(Drink::class, 'drink_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
