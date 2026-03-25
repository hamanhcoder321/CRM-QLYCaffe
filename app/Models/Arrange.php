<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Arrange extends Model
{
    protected $fillable = [
        'day',
        'name_arrange',
        'name_customer',
        'address',
        'phone_customer',
        'sale_user_id',
        'part_id',
        'team_id',
        'account_social',
        'user_id',
        'support_user_id',
        'type_arrange',
        'result',
        'reason_fail',
        'total_arrange',
        'branch_id',
    ];

    /** Người chốt lô hàng */
    public function saleUser()
    {
        return $this->belongsTo(User::class, 'sale_user_id');
    }

    /** Người bốc/nhận hàng */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /** Người phụ bốc hàng */
    public function supportUser()
    {
        return $this->belongsTo(User::class, 'support_user_id');
    }

    /** Bộ phận phụ trách */
    public function part()
    {
        return $this->belongsTo(Part::class, 'part_id');
    }

    /** Đội nhóm phụ trách */
    public function team()
    {
        return $this->belongsTo(Team::class, 'team_id');
    }

    /** Chi nhánh của lô hàng */
    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    /** Danh sách lô hàng giao */
    public function shipments()
    {
        return $this->hasMany(Shipment::class, 'arrange_id');
    }
}
