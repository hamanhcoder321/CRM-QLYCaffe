<?php

namespace App\Traits;

use App\Models\Scopes\BranchScope;
use Illuminate\Support\Facades\Auth;

trait BelongsToBranch
{
    /**
     * Boot the trait.
     */
    protected static function bootBelongsToBranch()
    {
        // Tự động gán branch_id khi tạo mới
        static::creating(function ($model) {
            if (Auth::check() && !$model->branch_id) {
                $model->branch_id = Auth::user()->branch_id;
            }
        });

        // Áp dụng Global Scope để lọc dữ liệu
        static::addGlobalScope(new BranchScope);
    }

    /**
     * Quan hệ với Branch
     */
    public function branch()
    {
        return $this->belongsTo(\App\Models\Branch::class, 'branch_id');
    }
}
