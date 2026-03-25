<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Recruitment extends Model
{
    protected $fillable = [
        'position_id',
        'part_id',
        'number',
        'prioritize',
        'deadline',
        'user_id',
        'social',
        'result',
        'status',
        'obstacle',
        'solution',
    ];

    public function position()
    {
        return $this->belongsTo(Position::class, 'position_id');
    }

    public function part()
    {
        return $this->belongsTo(Part::class, 'part_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function listRecruitments()
    {
        return $this->hasMany(ListRecruitment::class, 'recruitment_id');
    }
}
