<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ListRecruitment extends Model
{
    protected $table = 'lists_recruitments';

    protected $fillable = [
        'recruitment_id',
        'day',
        'name',
        'phone',
        'email',
        'birthday',
        'interview',
        'user_id',
        'result',
        'status',
        'day_work',
        'note',
    ];

    public function recruitment()
    {
        return $this->belongsTo(Recruitment::class, 'recruitment_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
