<?php

namespace Modules\User\Models;

use Illuminate\Database\Eloquent\Model;

class Timesheet extends Model
{
    protected $fillable = [
        'user_id',
        'day',
        'number',
        'hour',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
