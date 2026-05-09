<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToBranch;

class Storage extends Model
{
    use BelongsToBranch;

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

}
