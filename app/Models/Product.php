<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'shipment_id',
        'name',
        'number_in',
        'number_out',
        'price',
    ];

    public function shipment()
    {
        return $this->belongsTo(Shipment::class, 'shipment_id');
    }

    public function sellProducts()
    {
        return $this->hasMany(SellProduct::class, 'product_id');
    }
}
