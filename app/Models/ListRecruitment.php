<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToBranch;

class ListRecruitment extends Model
{
    use BelongsToBranch;

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
        'experience',
        'skills',
        'branch_id',
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
