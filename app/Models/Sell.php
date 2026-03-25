<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sell extends Model
{
    protected $fillable = [
        'shipment_id',
        'branch_id',
        'status',
        'sell_day',
        'shipment_revenue',
        'profit',
        'storage',
    ];

    /** Lô hàng của đơn bán */
    public function shipment()
    {
        return $this->belongsTo(Shipment::class, 'shipment_id');
    }

    /** Chi nhánh của đơn bán */
    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    /** Danh sách sản phẩm trong đơn bán */
    public function sellProducts()
    {
        return $this->hasMany(SellProduct::class, 'sell_id');
    }
}
