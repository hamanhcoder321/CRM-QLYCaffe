<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Recruitment extends Model
{
    public const PRIORITY_LOW = 0;
    public const PRIORITY_MEDIUM = 1;
    public const PRIORITY_HIGH = 2;

    public const STATUS_RECRUITING = 0;
    public const STATUS_DONE = 1;
    public const STATUS_LATE = 2;

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

    public function getPriorityLabel(): string
    {
        return match ((int) $this->prioritize) {
            self::PRIORITY_HIGH => 'Ưu tiên cao',
            self::PRIORITY_MEDIUM => 'Ưu tiên trung bình',
            default => 'Ưu tiên thấp',
        };
    }

    public function getPriorityBadgeClass(): string
    {
        return match ((int) $this->prioritize) {
            self::PRIORITY_HIGH => 'tag-high',
            self::PRIORITY_MEDIUM => 'tag-medium',
            default => 'tag-low',
        };
    }

    public function getStatusLabel(): string
    {
        return match ((int) $this->status) {
            self::STATUS_DONE => 'Hoàn thành',
            self::STATUS_LATE => 'Trễ',
            default => 'Đang tuyển',
        };
    }

    public function getStatusBadgeClass(): string
    {
        return match ((int) $this->status) {
            self::STATUS_DONE => 'badge-success',
            self::STATUS_LATE => 'badge-danger',
            default => 'badge-info',
        };
    }
}
