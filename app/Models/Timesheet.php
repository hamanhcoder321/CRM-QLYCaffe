<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToBranch;

class Timesheet extends Model
{
    use BelongsToBranch;

    protected $fillable = [
        'user_id',
        'branch_id',
        'day',
        'number',
        'hour',
        'shift',
        'note',
    ];

    protected $casts = [
        'day' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
