<?php
namespace App\Modules\Payment\Models;

use Illuminate\Database\Eloquent\Model;

class LichSuNapTienPayos extends Model
{
    protected $table = 'lich_su_payos';

    protected $fillable = [
        'id', 'bin', 'admin_id', 'accountNumber', 'accountName',
        'amount', 'description', 'orderCode', 'currency',
        'paymentLinkId', 'status', 'checkoutUrl', 'qrCode',
        'hinh_thuc_thanh_toan', 'trang_thai', 'article_id',
        'customer_name', 'customer_phone', 'mothod_service',
        'customer_note', 'service_id', 'ma_don', 'loai_don',
        'customer_email', 'so_tien', 'cancel', 'payment_id',
        'link', 'shop_id', 'so_thang',
        // Dùng cho BanHang
        'sell_id',
    ];
}
