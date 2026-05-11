<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Traits\BelongsToBranch;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;


class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes, BelongsToBranch;



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
        'type_accounts_id',
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
        return $this->belongsTo(Type_account::class, 'type_accounts_id');
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

    // ===== PHÂN QUYỀN =====

    /** Lấy tên loại tài khoản (tự load nếu chưa có) */
    public function getAccountTypeName(): string
    {
        if (!$this->relationLoaded('typeAccount')) {
            $this->load('typeAccount');
        }
        return $this->typeAccount?->name ?? '';
    }

    /** Lấy tên bộ phận (tự load nếu chưa có) */
    public function getPartName(): string
    {
        if (!$this->relationLoaded('part')) {
            $this->load('part');
        }
        return $this->part?->name ?? '';
    }

    /** Lấy tên vị trí (tự load nếu chưa có) */
    public function getPositionName(): string
    {
        if (!$this->relationLoaded('position')) {
            $this->load('position');
        }
        return $this->position?->name ?? '';
    }

    /** Super Admin - Giám đốc toàn quyền các chi nhánh */
    public function isSuperAdmin(): bool
    {
        $typeName = strtolower($this->getAccountTypeName());
        return $typeName === 'super admin' || $typeName === 'giám đốc';
    }

    /** Admin - Quản lý chi nhánh */
    public function isAdmin(): bool
    {
        $typeName = strtolower($this->getAccountTypeName());
        return $typeName === 'admin' || $typeName === 'quản lý';
    }

    /** Kiểm tra xem có phải là cấp nhận lương cứng (Quản lý/Giám đốc) hay không */
    public function isManagerSalary(): bool
    {
        return $this->isAdmin() || $this->isSuperAdmin();
    }

    /** Scope lọc Quản lý/Giám đốc */
    public function scopeManagers($query)
    {
        return $query->whereHas('typeAccount', function($q) {
            $q->where('name', 'like', '%Quản lý%')->orWhere('name', 'like', '%Giám đốc%')->orWhere('name', 'like', '%Admin%')->orWhere('name', 'like', '%Super Admin%');
        });
    }

    /** Scope lọc Nhân viên thường */
    public function scopeStaff($query)
    {
        return $query->whereHas('typeAccount', function($q) {
            $q->where('name', 'like', '%Nhân viên%');
        });
    }

    /** Tự động cho phép Super Admin hoặc Admin */
    public function isSuperAdminOrAdmin(): bool
    {
        return $this->isSuperAdmin() || $this->isAdmin();
    }

    /** Nhân viên kho */
    public function canAccessWarehouse(): bool
    {
        if ($this->isSuperAdminOrAdmin()) return true;
        return str_contains(strtolower($this->getPartName()), 'kho');
    }

    /** Nhân viên bán hàng (Bao gồm Bán hàng, Pha chế, Phục vụ) */
    public function canAccessSales(): bool
    {
        if ($this->isSuperAdminOrAdmin()) return true;
        $part = strtolower($this->getPartName());
        return str_contains($part, 'bán hàng') || 
               str_contains($part, 'sale') || 
               str_contains($part, 'pha chế') || 
               str_contains($part, 'phục vụ');
    }

    /** Nhân viên pha chế */
    public function canAccessBartender(): bool
    {
        if ($this->isSuperAdminOrAdmin()) return true;
        return str_contains(strtolower($this->getPartName()), 'pha chế');
    }

    /** Nhân viên phục vụ */
    public function canAccessServer(): bool
    {
        if ($this->isSuperAdminOrAdmin()) return true;
        return str_contains(strtolower($this->getPartName()), 'phục vụ');
    }
}
