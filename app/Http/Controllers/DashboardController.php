<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // ===== THỐNG KÊ TỔNG QUAN =====
        $tongLoHang    = DB::table('arranges')->count();
        $tongDonNhap   = DB::table('shipments')->count();
        $tongNhanVien  = DB::table('users')->where('status', 0)->count();
        $tongNCC       = DB::table('customers')->count();

        // ===== BIỂU ĐỒ CỘT: Lô hàng nhập theo tháng (6 tháng gần nhất) =====
        $loHangTheoThang = DB::table('arranges')
            ->selectRaw('MONTH(created_at) as thang, YEAR(created_at) as nam, COUNT(*) as so_lo, IFNULL(SUM(total_arrange),0) as tong_gia_tri')
            ->where('created_at', '>=', Carbon::now()->subMonths(6)->startOfMonth())
            ->groupByRaw('YEAR(created_at), MONTH(created_at)')
            ->orderByRaw('YEAR(created_at), MONTH(created_at)')
            ->get();

        $barLabels  = $loHangTheoThang->map(fn($r) => 'T' . $r->thang . '/' . $r->nam)->values()->toArray();
        $barSoLo    = $loHangTheoThang->pluck('so_lo')->toArray();
        $barGiaTri  = $loHangTheoThang->map(fn($r) => round($r->tong_gia_tri / 1_000_000, 1))->toArray(); // Triệu đồng

        // ===== BIỂU ĐỒ TRÒN: Kết quả lô hàng =====
        $ketQuaLoHang = DB::table('arranges')
            ->selectRaw("
                SUM(CASE WHEN result = 0 THEN 1 ELSE 0 END) as nhap_lieu,
                SUM(CASE WHEN result = 1 THEN 1 ELSE 0 END) as hoan_thanh,
                SUM(CASE WHEN result = 2 THEN 1 ELSE 0 END) as that_bai
            ")
            ->first();

        $pieData = [
            $ketQuaLoHang->hoan_thanh ?? 0,
            $ketQuaLoHang->nhap_lieu  ?? 0,
            $ketQuaLoHang->that_bai   ?? 0,
        ];

        // ===== BIỂU ĐỒ CỘT NGANG: Nhân viên theo bộ phận =====
        $nvTheoBoPhan = DB::table('users')
            ->join('parts', 'users.part_id', '=', 'parts.id')
            ->selectRaw('parts.name as bo_phan, COUNT(*) as so_nv')
            ->where('users.status', 0)
            ->groupBy('parts.id', 'parts.name')
            ->orderByDesc('so_nv')
            ->limit(8)
            ->get();

        $hbarLabels = $nvTheoBoPhan->pluck('bo_phan')->toArray();
        $hbarData   = $nvTheoBoPhan->pluck('so_nv')->toArray();

        return view('dashboard', compact(
            'tongLoHang', 'tongDonNhap', 'tongNhanVien', 'tongNCC',
            'barLabels', 'barSoLo', 'barGiaTri',
            'pieData',
            'hbarLabels', 'hbarData'
        ));
    }
}
