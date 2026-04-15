<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        // 1. NHẬP NGUYÊN LIỆU VỀ KHO (Đã chuyển xử lý sang NhapHangSeeder)

        // 2. TẠO MENU THỨC UỐNG ĐỂ BÁN (products)
        // 2. TẠO MENU THỨC UỐNG ĐỂ BÁN (products)
        $thucUong = [
            ['name' => 'Cafe đen đá',         'price' => 25000, 'cost_price' => 7000],
            ['name' => 'Cafe đen nóng',       'price' => 25000, 'cost_price' => 7000],
            ['name' => 'Cafe sữa đá',         'price' => 30000, 'cost_price' => 10000],
            ['name' => 'Cafe sữa nóng',       'price' => 30000, 'cost_price' => 10000],
            ['name' => 'Cafe không đường',    'price' => 25000, 'cost_price' => 7000],
            ['name' => 'Bạc xỉu đá',          'price' => 35000, 'cost_price' => 12000],
            ['name' => 'Espresso',            'price' => 45000, 'cost_price' => 15000],
            ['name' => 'Capuchino',           'price' => 55000, 'cost_price' => 18000],
            ['name' => 'Latte',               'price' => 55000, 'cost_price' => 18000],
            ['name' => 'Americano',           'price' => 40000, 'cost_price' => 12000],
            ['name' => 'Trà sữa trân châu',   'price' => 45000, 'cost_price' => 15000],
            ['name' => 'Trà sữa Thái xanh',   'price' => 45000, 'cost_price' => 16000],
            ['name' => 'Trà sữa Matcha',      'price' => 50000, 'cost_price' => 17000],
            ['name' => 'Trà đào cam sả',      'price' => 45000, 'cost_price' => 14000],
            ['name' => 'Trà sen vàng',        'price' => 45000, 'cost_price' => 15000],
            ['name' => 'Cà phê ủ lạnh (Cold Brew)', 'price' => 65000, 'cost_price' => 22000],
        ];

        $productData = [];
        foreach ($thucUong as $tu) {
            $productData[] = [
                'name'        => $tu['name'],
                'price'       => $tu['price'],
                'cost_price'  => $tu['cost_price'],
                'number_in'   => 10000,
                'number_out'  => 0,
                'created_at'  => now(),
                'updated_at'  => now(),
            ];
        }

        DB::table('products')->truncate();
        DB::table('products')->insert($productData);
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->command->info('✅ Đã tạo dữ liệu Kho nguyên liệu & Menu Thức uống thành công!');
    }
}
