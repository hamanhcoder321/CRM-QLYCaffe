<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    protected $fillable = [
        'name',
        'address',
        'phone',
        'manager_id',
        'status',
    ];

    /** Chi nhánh do quản lý nào phụ trách */
    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    /** Danh sách nhân viên thuộc chi nhánh */
    public function users()
    {
        return $this->hasMany(User::class, 'branch_id');
    }

    /** Danh sách đơn bán thuộc chi nhánh */
    public function sells()
    {
        return $this->hasMany(Sell::class, 'branch_id');
    }

    /** Kho thuộc chi nhánh */
    public function storages()
    {
        return $this->hasMany(Storage::class, 'branch_id');
    }

    /** Chi phí thuộc chi nhánh */
    public function totalFees()
    {
        return $this->hasMany(TotalFee::class, 'branch_id');
    }

    /** Lô hàng thuộc chi nhánh */
    public function arranges()
    {
        return $this->hasMany(Arrange::class, 'branch_id');
    }
}
