<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

class BranchScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        // Tránh vòng lặp vô tận: Chỉ áp dụng scope khi user ĐÃ được xác thực (không áp dụng khi đang truy vấn để login)
        if (auth()->hasUser()) {
            $user = auth()->user();

            // Nếu là Super Admin thì xem được hết (trừ khi có lọc cụ thể trong session)
            // Lưu ý: getAccountTypeName() hoặc isSuperAdmin() là các hàm tôi đã thấy trong User model
            if (method_exists($user, 'isSuperAdmin') && $user->isSuperAdmin()) {
                
                // Nếu Super Admin chọn một chi nhánh cụ thể từ Session (sẽ làm ở bước sau)
                if (session()->has('selected_branch_id')) {
                    $builder->where($model->getTable() . '.branch_id', session('selected_branch_id'));
                }
                
                return;
            }

            // Nếu không phải Super Admin, lọc theo branch_id của User hoặc dữ liệu chung (branch_id là null)
            if ($user->branch_id) {
                $builder->where(function($q) use ($model, $user) {
                    $q->where($model->getTable() . '.branch_id', $user->branch_id)
                      ->orWhereNull($model->getTable() . '.branch_id');
                });
            }
        }
    }
}
