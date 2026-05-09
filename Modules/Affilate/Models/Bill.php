<?php

namespace App\Modules\Affilate\Models;

use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    protected $table = 'bills';

    protected $fillable = [
        'status',
        'ma_don_hang',
        'user_id',
        'shop_id',
        'total_price',
        'address',
        'tel',
        'shipping_fee',
        'user_name',
        'pttt',
        'note',
        'email',
        'is_read',
        'to_district_id',
        'to_ward_id',
        'shipping_carrier'
    ];

    public function details()
    {
        return $this->hasMany(Bill_detail::class,'bill_id');
    }
    public function shop()
    {
        return $this->belongsTo(\App\Modules\Affilate\Models\Shop::class,'shop_id');
    }

    public  function user(){
        return $this->belongsTo(User::class,'user_id');
    }
   
}
