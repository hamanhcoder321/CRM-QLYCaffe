<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Storage extends Model
{
    protected $fillable = [
        'shipment_id',
        'branch_id',
        'name_storage',
    ];

    /** Lô hàng trong kho */
    public function shipment()
    {
        return $this->belongsTo(Shipment::class, 'shipment_id');
    }

    /** Chi nhánh của kho */
    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}
