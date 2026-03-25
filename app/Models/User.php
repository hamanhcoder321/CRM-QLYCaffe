<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'birthday',
        'sex',
        'part_id',
        'position_id',
        'type_work',
        'team_id',
        'phone',
        'address',
        'status',
        'start_day',
        'end_day',
        'type_account_id',
        'branch_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];


    /**khoá ngoại model user */

    public function part()
    {
        return $this->belongsTo(Part::class, 'part_id');
    }
    public function position()
    {
        return $this->belongsTo(Position::class, 'position_id');
    }
    public function team()
    {
        return $this->belongsTo(Team::class, 'team_id');
    }
    public function typeAccount()
    {
        return $this->belongsTo(typeAccount::class, 'type_account_id');
    }

    /** Chi nhánh của nhân viên */
    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }


    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
