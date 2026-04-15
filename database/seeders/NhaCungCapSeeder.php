<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Customer;

class NhaCungCapSeeder extends Seeder
{
    public function run(): void
    {
        $nccs = [
            [
                'fullname'     => 'Đại lý Nguyên Liệu Cafe Hưng Phát',
                'phone'        => '0988111222',
                'address'      => '120/5 Đường 3/2, Quận 10, TP.Hà Nội',
                'source'       => 'Trực tiếp / Cửa hàng',
                'product_sale' => 'Hạt cafe, Máy móc pha chế, Syrup',
                'classify'     => 1, // còn hàng
                'scale'        => 2, // Lớn
                'potentical'   => 2, // Cao
                'note'         => 'Nhà cung cấp chính các loại cafe hạt Robusta và Arabica xuất khẩu.',
            ],
            [
                'fullname'     => 'Nhà phân phối Sữa Vinamilk Thủ Đức',
                'phone'        => '0933444555',
                'address'      => 'Đường Pasteur, P.Bình Thọ, Tp.Thủ Đức',
                'source'       => 'Giới thiệu / Sale',
                'product_sale' => 'Sữa tươi, Sữa đặc, Kem béo',
                'classify'     => 1,
                'scale'        => 2,
                'potentical'   => 2,
                'note'         => 'Chuyên cung ứng lô lớn sữa phục vụ pha chế.',
            ],
            [
                'fullname'     => 'Cơ sở sản xuất Trà xanh Bảo Lộc',
                'phone'        => '0911222333',
                'address'      => 'Quốc lộ 20, Tp.Bảo Lộc, Hà Nội',
                'source'       => 'Online / Facebook',
                'product_sale' => 'Trà xanh, Trà oolong, Trà lài',
                'classify'     => 1,
                'scale'        => 1, // Vừa
                'potentical'   => 1, // TB
                'note'         => 'Hàng gửi theo xe tải vào thứ 2 hàng tuần.',
            ],
            [
                'fullname'     => 'NCC Ly nhựa & Bao bì Eco',
                'phone'        => '0909666777',
                'address'      => 'KCN Ngọc Hồi, Thanh Trì, Hà Nội',
                'source'       => 'Google Search',
                'product_sale' => 'Ly giấy, Bao bì tự huỷ, Ống hút',
                'classify'     => 1,
                'scale'        => 1,
                'potentical'   => 2,
                'note'         => 'Thân thiện môi trường, chiết khấu 10% nếu nhập trên 1 vạn.',
            ],
            [
                'fullname'     => 'Trân châu 3Q Đài Loan',
                'phone'        => '0901999888',
                'address'      => 'Kho tổng Lạng Sơn',
                'source'       => 'Shopee B2B',
                'product_sale' => 'Trân châu đen, topping, thạch',
                'classify'     => 1,
                'scale'        => 2,
                'potentical'   => 2,
                'note'         => 'Hàng nhập khẩu trực tiếp.',
            ],
            [
                'fullname'     => 'Công ty Đường Biên Hòa',
                'phone'        => '0912121212',
                'address'      => 'KCN Biên Hòa, Đồng Nai',
                'source'       => 'Hợp đồng công ty',
                'product_sale' => 'Đường tinh luyện, đường phèn',
                'classify'     => 1,
                'scale'        => 2,
                'potentical'   => 2,
                'note'         => 'Ký hợp đồng theo quý 3 tháng 1 lần.',
            ],
            [
                'fullname'     => 'Xưởng Nước Đá Tinh Khiết Mát Lạnh',
                'phone'        => '0922334455',
                'address'      => 'Cách cửa hàng 2km',
                'source'       => 'Trực tiếp',
                'product_sale' => 'Đá viên, đá bi',
                'classify'     => 1,
                'scale'        => 0, // Nhỏ
                'potentical'   => 1,
                'note'         => 'Giao hàng mỗi ngày lúc 6h sáng và 1h chiều.',
            ]
        ];

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('customers')->truncate();
        
        foreach ($nccs as $ncc) {
            $ncc['created_at'] = now();
            $ncc['updated_at'] = now();
            DB::table('customers')->insert($ncc);
        }
        
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->command->info('✅ Bơm tài liệu nhà cung cấp (khách hàng) thành công!');
    }
}
