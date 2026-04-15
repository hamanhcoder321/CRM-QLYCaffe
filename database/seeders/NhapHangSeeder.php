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

        // "đường + hạt caffe + trân trâu + sữa + đá"
        $loaiHang = [
            ['ten' => 'Hạt caffe (Arabica/Robusta)', 'prefix' => 'CF'],
            ['ten' => 'Đường kính trắng',            'prefix' => 'DG'],
            ['ten' => 'Trân châu đen',               'prefix' => 'TC'],
            ['ten' => 'Sữa đặc / Sữa tươi',          'prefix' => 'SU'],
            ['ten' => 'Đá viên sạch',                'prefix' => 'DA'],
        ];

        $kqLabels = [
            ['result' => 1, 'reason_fail' => null], // Hoàn thành
            ['result' => 1, 'reason_fail' => null],
            ['result' => 0, 'reason_fail' => null], // Đang xử lý
        ];

        $nhaCungCap = ['NCC Minh Châu', 'NCC Bình Minh', 'Đại lý Cafe Hà nội'];
        $phones     = ['0901234567', '0912345678', '0923456789'];
        $accounts   = ['Zalo Kho', 'Facebook NCC', 'Hotline Đại Lý'];

        $rows = [];
        $insertedArrangesRaw = [];

        for ($i = 0; $i < 20; $i++) {
            $hang = $loaiHang[array_rand($loaiHang)];
            $kq   = $kqLabels[array_rand($kqLabels)];
            $soLo = $hang['prefix'] . '-' . rand(1000, 9999);

            $rows[] = [
                'day'             => Carbon::now()->subDays(rand(1, 30))->format('Y-m-d'),
                'name_arrange'    => 'Nhập lô ' . $soLo . ' — ' . $hang['ten'],
                'name_customer'   => $nhaCungCap[array_rand($nhaCungCap)],
                'address'         => 'Hà Nội',
                'phone_customer'  => $phones[array_rand($phones)],
                'sale_user_id'    => $userIds[array_rand($userIds)],
                'part_id'         => $partIds[array_rand($partIds)],
                'team_id'         => $teamIds[array_rand($teamIds)],
                'account_social'  => $accounts[array_rand($accounts)],
                'user_id'         => $userIds[array_rand($userIds)],
                'support_user_id' => $userIds[array_rand($userIds)],
                'type_arrange'    => 1, // 1 có thể là Nhập
                'result'          => $kq['result'],
                'reason_fail'     => $kq['reason_fail'],
                'total_arrange'   => rand(500, 5000) * 1000,
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
            $insertedArrangesRaw[] = $hang['ten'];
        }

        DB::table('arranges')->insert($rows);

        // Tạo records tương ứng trong shipments
        $newIds = DB::table('arranges')->orderBy('id', 'desc')->take(20)->pluck('id')->toArray();
        $newIds = array_reverse($newIds); // đảo lại trật tự

        $shipments = [];
        foreach ($newIds as $id) {
            $shipments[] = [
                'arrange_id'  => $id,
                'customer_id' => null,
                'car_money'   => rand(1, 5) * 50000,
                'created_at'  => now(),
                'updated_at'  => now(),
            ];
        }

        DB::table('shipments')->insert($shipments);

        // Tạo records tương ứng trong storages (Sau đó nhập về kho)
        $shipmentIds = DB::table('shipments')->orderBy('id', 'desc')->take(20)->pluck('id')->toArray();
        $shipmentIds = array_reverse($shipmentIds);

        $storages = [];
        foreach ($shipmentIds as $idx => $sId) {
            $tenHang = $insertedArrangesRaw[$idx] ?? 'Nguyên liệu không xác định';
            $storages[] = [
                'shipment_id'  => $sId,
                'name_storage' => $tenHang,
                'created_at'   => now(),
                'updated_at'   => now(),
            ];
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('storages')->truncate(); // Dọn kho cũ (nếu có) do lúc nãy MenuSeeder tạo ko có dính líu nhập hàng
        DB::table('storages')->insert($storages);
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->command->info('✅ Seed ' . count($rows) . ' lô hàng nguyên liệu, ' . count($shipments) . ' phiếu nhập và đẩy ' . count($storages) . ' mã vào kho thành công!');
    }
}
