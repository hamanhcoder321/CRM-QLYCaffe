<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToBranch;

class TotalFee extends Model
{
    use BelongsToBranch;

    protected $fillable = [
        'day',
        'content',
        'money',
        'type_fee_id',
        'atm_id',
        'branch_id',
    ];

    /** Loại chi phí (thu/chi) */
    public function typeFee()
    {
        return $this->belongsTo(TypeFee::class, 'type_fee_id');
    }

    /** Tài khoản ngân hàng */
    public function atm()
    {
        return $this->belongsTo(Atm::class, 'atm_id');
    }

}
