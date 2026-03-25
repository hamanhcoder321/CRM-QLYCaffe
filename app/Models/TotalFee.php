<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TotalFee extends Model
{
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

    /** Chi nhánh phát sinh chi phí */
    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}
