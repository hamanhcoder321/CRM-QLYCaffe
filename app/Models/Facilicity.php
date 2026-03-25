<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Facilicity extends Model
{
    protected $table = '_facilitices';

    protected $fillable = [
        'name',
        'number',
        'description',
        'status',
        'position',
        'need_user_id',
        'manager_user_id',
        'day',
        'note',
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
