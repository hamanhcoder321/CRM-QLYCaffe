<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shipment extends Model
{
    protected $fillable = [
        'arrange_id',
        'customer_id',
        'car_money',
    ];

    public function arrange()
    {
        return $this->belongsTo(Arrange::class, 'arrange_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'shipment_id');
    }

    public function sells()
    {
        return $this->hasMany(Sell::class, 'shipment_id');
    }

    public function storages()
    {
        return $this->hasMany(Storage::class, 'shipment_id');
    }
}
