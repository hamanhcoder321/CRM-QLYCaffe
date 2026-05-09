<?php

namespace App\Modules\Affilate\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use App\Modules\Affilate\Models\Category;
use App\Modules\Affilate\Models\ThucDon;
use App\Modules\Affilate\Models\Product;
use App\Modules\Affilate\Models\ShopUser;
use App\Modules\Affilate\Models\Tag; // Thêm import cho Tag
use App\Models\Bill;
use Illuminate\Notifications\Notifiable; // Thêm trait Notifiable

class Shop extends Model
{
    use Notifiable; // Sử dụng trait để hỗ trợ gửi thông báo

    protected $table = 'shop';

    protected $fillable = [
        'ten',
        'ma_so_thue',
        'so_dinh_danh',
        'cn_kinh_doanh',
        'email',
        'slug',
        'address',
        'category_id',
        'user_id',
        'nganh_hang',
        'thoi_gian_mo_cua',
        'thoi_gian_dong_cua',
        'anh',
        'cn_kinh_doanh',
        'phi_van_chuyen',
        'thoi_gian_kich_hoat',
        'ngay_gia_han',
        'ngay_het_han',
        'status',
        'order_no',
        'tag_id',
        'tell'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function thucdons()
    {
        return $this->hasMany(ThucDon::class, 'shop_id');
    }

    public function bills()
    {
        return $this->hasMany(Bill::class, 'shop_id');
    }

    public function products()
    {
        return $this->hasManyThrough(Product::class, ThucDon::class);
    }
    public function tag()
    {
        return $this->belongsTo(Tag::class, 'tag_id');
    }
    public function shop_user()
    {
        return $this->hasMany(ShopUser::class, 'shop_id','id');
    }

    public function routeNotificationForMail()
    {
        return $this->user->email; // Sử dụng email của User liên kết
    }

    /**
     * Scope để lấy các shop đã hết hạn
     */
    public function scopeExpired($query)
    {
        return $query->where('ngay_het_han', '<=', now());
    }

    /**
     * Scope để lấy các shop còn hiệu lực
     */
    public function scopeActive($query)
    {
        return $query->where('ngay_het_han', '>', now());
    }

    /**
     * Attribute để kiểm tra shop có hết hạn không
     */
    public function getIsExpiredAttribute()
    {
        return $this->ngay_het_han <= now();
    }

    /**
     * Attribute để lấy số ngày còn lại
     */
    public function getDaysRemainingAttribute()
    {
        $remaining = now()->diffInDays($this->ngay_het_han, false);
        return $remaining > 0 ? $remaining : 0;
    }
}