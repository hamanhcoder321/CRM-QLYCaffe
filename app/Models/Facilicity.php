<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToBranch;

class Facilicity extends Model
{
    use BelongsToBranch;

    protected $table = '_facilitices';

    protected $fillable = [
        'name',
        'image',
        'number',
        'description',
        'status',
        'position',
        'need_user_id',
        'manager_user_id',
        'day',
        'note',
        'branch_id',
    ];

    /** Người phụ trách */
    public function needUser()
    {
        return $this->belongsTo(User::class, 'need_user_id');
    }

    /** Người bàn giao */
    public function managerUser()
    {
        return $this->belongsTo(User::class, 'manager_user_id');
    }
}
