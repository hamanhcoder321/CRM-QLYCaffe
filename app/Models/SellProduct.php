<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SellProduct extends Model
{
    protected $fillable = [
        'product_id',
        'sell_id',
        'sell_day',
        'fullname_customer',
        'number_sell',
        'price_sell',
        'revenue',
        'atm_id',
        'number_produts',
        'bagging',
        'number_bagging',
        'transport',
        'note',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function sell()
    {
        return $this->belongsTo(Sell::class, 'sell_id');
    }

    public function atm()
    {
        return $this->belongsTo(Atm::class, 'atm_id');
    }
}
