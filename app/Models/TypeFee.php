<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TypeFee extends Model
{
    protected $table = 'type_fees';

    protected $fillable = [
        'name',
        'code',
        'type',
    ];

    public function totalFees()
    {
        return $this->hasMany(TotalFee::class, 'type_fee_id');
    }
}
