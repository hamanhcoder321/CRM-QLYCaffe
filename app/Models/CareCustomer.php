<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CareCustomer extends Model
{
    protected $table = 'care_customers';

    protected $fillable = [
        'day',
        'action',
        'result',
        'note',
    ];

    public function customers()
    {
        return $this->hasMany(Customer::class, 'care_customer_id');
    }
}
