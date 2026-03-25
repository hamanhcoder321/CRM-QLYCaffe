<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalaryMechanism extends Model
{
    protected $table = 'salary_mechanism';

    protected $fillable = [
        'user_id',
        'salary',
        'ot_day',
        'ot_hour',
        'responsibility',
        'enthusiasm',
        'support',
        'salary_keep',
        'salary_need_keep',
        'month_keep',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
