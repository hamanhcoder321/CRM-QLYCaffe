<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Atm extends Model
{
    protected $fillable = [
        'name',
        'code',
        'money_start',
        'money_now',
    ];

    public function totalFees()
    {
        return $this->hasMany(TotalFee::class, 'atm_id');
    }

    public function sellProducts()
    {
        return $this->hasMany(SellProduct::class, 'atm_id');
    }
}
