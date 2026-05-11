<?php
namespace App\Modules\AI\Services;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DatabaseAIService
{
    protected $ai;

    public function __construct()
    {
        $this->ai = new OpenAIService();
    }

    public function askDatabase(string $question): string
    {
        try {
            $today     = Carbon::today()->toDateString();
            $thisMonth = Carbon::now()->format('Y-m');

            // ===== DỮ LIỆU CĂN BẢN (nhỏ gọn) =====
            $chiNhanh  = DB::table('branches')->get(['id', 'name']);
            $boPhan    = DB::table('parts')->get(['id', 'name']);
            $chucVu    = DB::table('positions')->get(['id', 'name']);

            // Nhân viên
            $nhanVien  = DB::table('users')
                ->whereNull('deleted_at')
                ->get(['id', 'name', 'email', 'branch_id', 'part_id', 'position_id', 'status']);

            // Thực đơn
            $thucDon   = DB::table('drinks')->get(['id', 'name', 'price', 'status']);

            // Kho: chỉ lấy 20 nguyên liệu
            $kho       = DB::table('products')
                ->orderBy('number_in', 'desc')
                ->limit(20)
                ->get(['id', 'name', 'number_in', 'number_out', 'cost_price']);

            // Doanh thu tổng hợp
            $doanhThuHomNay = DB::table('sells')
                ->where('status', 1)
                ->where('sell_day', $today)
                ->sum('shipment_revenue');

            $doanhThuThangNay = DB::table('sells')
                ->where('status', 1)
                ->whereRaw("DATE_FORMAT(sell_day, '%Y-%m') = ?", [$thisMonth])
                ->sum('shipment_revenue');

            $doanhThuTheoNgay = DB::table('sells')
                ->where('status', 1)
                ->orderBy('sell_day', 'desc')
                ->limit(30)
                ->get(['sell_day', 'shipment_revenue', 'branch_id']);

            // Chi phí tổng hợp
            $chiPhiHomNay = DB::table('total_fees')
                ->where('day', $today)
                ->sum('money');

            $chiPhiThangNay = DB::table('total_fees')
                ->whereRaw("DATE_FORMAT(day, '%Y-%m') = ?", [$thisMonth])
                ->sum('money');

            // Tuyển dụng
            $tuyenDung = DB::table('recruitments')
                ->get(['id', 'position_id', 'part_id', 'number', 'status', 'branch_id', 'deadline']);

            $hoSo = DB::table('lists_recruitments')
                ->get(['id', 'name', 'phone', 'email', 'status', 'result', 'recruitment_id']);

            // Tổng số giao dịch
            $tongGiaoDich = DB::table('sells')->where('status', 1)->count();
            $tongGiaoDichHomNay = DB::table('sells')->where('status', 1)->where('sell_day', $today)->count();

            // Đóng gói context gọn
            $context = [
                'hom_nay'            => $today,
                'thang_nay'          => $thisMonth,
                'chi_nhanh'          => $chiNhanh,
                'bo_phan'            => $boPhan,
                'chuc_vu'            => $chucVu,
                'nhan_vien'          => $nhanVien,
                'thuc_don'           => $thucDon,
                'kho_nguyen_lieu_top20' => $kho,
                'doanh_thu_hom_nay'  => $doanhThuHomNay,
                'doanh_thu_thang_nay'=> $doanhThuThangNay,
                'doanh_thu_30_ngay_gan_nhat' => $doanhThuTheoNgay,
                'chi_phi_hom_nay'    => $chiPhiHomNay,
                'chi_phi_thang_nay'  => $chiPhiThangNay,
                'tong_giao_dich'     => $tongGiaoDich,
                'tong_giao_dich_hom_nay' => $tongGiaoDichHomNay,
                'tuyen_dung'         => $tuyenDung,
                'ho_so_ung_vien'     => $hoSo,
            ];

        } catch (\Throwable $e) {
            \Log::error('[AI] Loi query database: ' . $e->getMessage());
            return 'Loi khi doc du lieu: ' . $e->getMessage();
        }

        $jsonData = json_encode($context, JSON_UNESCAPED_UNICODE);

        $systemPrompt = "Ban la tro ly AI thong minh cua he thong CRM Chuoi Cafe.

DU LIEU THONG KE HE THONG (cap nhat thoi gian thuc):
{$jsonData}

HUONG DAN XU LY DU LIEU:
1. Tu dong map khoa ngoai: branch_id -> chi_nhanh, part_id -> bo_phan, position_id -> chuc_vu.
2. Y NGHIA STATUS CHINH XAC THEO TUNG BANG (rat quan trong):
   - Bang nhan_vien (users):         status=0 la DANG LAM VIEC, status=1 la DA NGHI VIEC.
   - Bang thuc_don (drinks):         status=0 la NGUNG BAN,     status=1 la DANG BAN.
   - Bang tuyen_dung (recruitments): status=0 la DANG TUYEN,    status=1 la HOAN THANH, status=2 la TRE HAN.
   - Bang ho_so_ung_vien:            result=0  la KHONG DAT,    result=1  la DAT.
   - Bang doanh_thu (sells):         status=1  la DA BAN.
3. Doanh thu da duoc tinh san (doanh_thu_hom_nay, doanh_thu_thang_nay...), dung luon khong can tinh lai.
4. Don vi tien te la VND, dinh dang so co dau phay (vi du: 1,500,000 VND).

CACH TRA LOI:
- Tra loi bang tieng Viet, chinh xac, chuyen nghiep.
- Neu can bao cao/danh sach, trinh bay bang Markdown Table hoac gach dau dong.
- KHONG giai thich cach tim du lieu, chi dua ra ket qua.
- Neu chua co du lieu, thong bao lich su.";

        $prompt = "CAU HOI: {$question}";

        try {
            return $this->ai->chat($prompt, $systemPrompt, 2000);
        } catch (\Throwable $e) {
            return 'Loi khi AI xu ly du lieu: ' . $e->getMessage();
        }
    }
}
