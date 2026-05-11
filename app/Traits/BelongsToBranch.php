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
        static::creating(function ($model) {
            if (Auth::check() && empty($model->branch_id)) {
                $user = Auth::user();
                if (method_exists($user, 'isSuperAdmin') && $user->isSuperAdmin()) {
                    if (session()->has('selected_branch_id') && session('selected_branch_id') !== 'all') {
                        $model->branch_id = session('selected_branch_id');
                    }
                } else {
                    $model->branch_id = $user->branch_id;
                }
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
