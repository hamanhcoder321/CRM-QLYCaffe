<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class NhapHangSeeder extends Seeder
{
    public function run(): void
    {
        $partIds = DB::table('parts')->pluck('id')->toArray() ?: [null];
        $teamIds = DB::table('teams')->pluck('id')->toArray() ?: [null];
        $userIds = DB::table('users')->pluck('id')->toArray() ?: [null];

        // Hàng hóa thực tế của quán cafe
        $loaiHang = [
            ['ten' => 'Cà phê Arabica rang xay',    'prefix' => 'CA'],
            ['ten' => 'Cà phê Robusta nguyên chất', 'prefix' => 'CR'],
            ['ten' => 'Sữa tươi Vinamilk 1L',       'prefix' => 'ST'],
            ['ten' => 'Đường trắng tinh luyện',      'prefix' => 'DT'],
            ['ten' => 'Ly nhựa 300ml',               'prefix' => 'LY'],
            ['ten' => 'Ống hút giấy sinh thái',      'prefix' => 'OH'],
            ['ten' => 'Trà lài Thái Nguyên',         'prefix' => 'TL'],
            ['ten' => 'Bột matcha Nhật Bản',         'prefix' => 'MB'],
            ['ten' => 'Đá viên túi 5kg',             'prefix' => 'DV'],
            ['ten' => 'Syrup dâu tây Monin',         'prefix' => 'SD'],
            ['ten' => 'Trân châu đen nấu sẵn',       'prefix' => 'TC'],
            ['ten' => 'Cốc giấy kraft có nắp',       'prefix' => 'CG'],
            ['ten' => 'Kem béo số 3 (creamer)',      'prefix' => 'KB'],
            ['ten' => 'Hương vani tổng hợp',         'prefix' => 'HV'],
            ['ten' => 'Bột cacao nguyên chất',       'prefix' => 'BC'],
        ];

        $kqLabels = [
            ['result' => 1, 'reason_fail' => null],                         // Hoàn thành
            ['result' => 1, 'reason_fail' => null],
            ['result' => 1, 'reason_fail' => null],
            ['result' => 0, 'reason_fail' => null],                         // Nhập liệu
            ['result' => 2, 'reason_fail' => 'hàng hết hạn sử dụng'],      // Thất bại
            ['result' => 2, 'reason_fail' => 'nhà cung cấp hủy đơn'],
        ];

        $nhaCungCap = [
            'Trần Thị Hoa',    'Nguyễn Văn Nam',  'Lê Minh Tuấn',
            'Phạm Thị Lan',    'Hoàng Văn Đức',   'Vũ Thị Mai',
            'Đặng Quốc Bình',  'Bùi Thị Ngọc',    'Võ Minh Tâm',
        ];

        $phones = [
            '0901234567', '0912345678', '0923456789',
            '0934567890', '0945678901', '0856789012',
            '0867890123', '0878901234', '0889012345',
        ];

        $accounts = [
            'Zalo Cty Minh Châu',      'Shopee Mall NCC',
            'acc Tuấn Ngọc',            'Facebook Kho Bình Minh',
            'Email đặt hàng NCC',       'Hotline đại lý cafe',
        ];

        $addresses = [
            'KCN Bình Dương, P.Thuận Giao, Bình Dương',
            '45 Lý Thái Tổ, Q.Hoàn Kiếm, Hà Nội',
            'Khu CN Mộc Châu, Sơn La',
            '128 Nguyễn Văn Linh, Q.7, TP.HCM',
            'Quốc lộ 1A, Hải Phòng',
            'Làng nghề trà Tân Cương, Thái Nguyên',
            '22 Lê Duẩn, Q.Hai Bà Trưng, Hà Nội',
            '305 Điện Biên Phủ, Q.Bình Thạnh, TP.HCM',
        ];

        $rows = [];
        for ($i = 0; $i < 20; $i++) {
            $hang = $loaiHang[array_rand($loaiHang)];
            $kq   = $kqLabels[array_rand($kqLabels)];
            $soLo = $hang['prefix'] . '-' . rand(100, 999);

            $rows[] = [
                'day'             => Carbon::now()->subDays(rand(1, 90))->format('Y-m-d'),
                'name_arrange'    => 'Lô ' . $soLo . ' — ' . $hang['ten'],
                'name_customer'   => $nhaCungCap[array_rand($nhaCungCap)],
                'address'         => $addresses[array_rand($addresses)],
                'phone_customer'  => $phones[array_rand($phones)],
                'sale_user_id'    => $userIds[array_rand($userIds)],
                'part_id'         => $partIds[array_rand($partIds)],
                'team_id'         => $teamIds[array_rand($teamIds)],
                'account_social'  => $accounts[array_rand($accounts)],
                'user_id'         => $userIds[array_rand($userIds)],
                'support_user_id' => $userIds[array_rand($userIds)],
                'type_arrange'    => rand(0, 1),
                'result'          => $kq['result'],
                'reason_fail'     => $kq['reason_fail'],
                'total_arrange'   => rand(500, 15000) * 1000,
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
        }

        DB::table('arranges')->insert($rows);

        // Tự động tạo shipment liên kết
        $newIds    = DB::table('arranges')->orderBy('id', 'desc')->take(20)->pluck('id');
        $shipments = $newIds->map(fn($id) => [
            'arrange_id'  => $id,
            'customer_id' => null,
            'car_money'   => rand(0, 5) * 100000,
            'created_at'  => now(),
            'updated_at'  => now(),
        ])->toArray();

        DB::table('shipments')->insert($shipments);

        $this->command->info('✅ Seed ' . count($rows) . ' lô hàng cafe + ' . count($shipments) . ' đơn nhập thành công!');
    }
}
