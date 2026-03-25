<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'fullname',
        'phone',
        'address',
        'source',
        'classify',
        'product_sale',
        'scale',
        'care_customer_id',
        'information',
        'potentical',
        'note',
    ];

    public function careCustomer()
    {
        return $this->belongsTo(CareCustomer::class, 'care_customer_id');
    }

    public function shipments()
    {
        return $this->hasMany(Shipment::class, 'customer_id');
    }
}
