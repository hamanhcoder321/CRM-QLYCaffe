<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class FullDataSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Bộ phận (parts)
        $partIds = [];
        foreach ([
            'Pha chế', 'Phục vụ', 'Kho', 'Bán hàng'
        ] as $name) {
            $partIds[] = DB::table('parts')->insertGetId(['name' => $name, 'created_at' => now(), 'updated_at' => now()]);
        }

        // 2. Vị trí (positions)
        $positionIds = [];
        foreach ([
            'Nhân viên', 'Quản lý chi nhánh', 'Giám đốc'
        ] as $name) {
            $positionIds[] = DB::table('positions')->insertGetId(['name' => $name, 'created_at' => now(), 'updated_at' => now()]);
        }

        // 3. Đội nhóm (teams)
        $teamIds = [];
        foreach ([
            'Ca Sáng', 'Ca Chiều', 'Ca Kho'
        ] as $name) {
            $teamIds[] = DB::table('teams')->insertGetId(['name' => $name, 'created_at' => now(), 'updated_at' => now()]);
        }

        // 4. Loại tài khoản (type_accounts)
        $typeIds = [];
        foreach (['Super Admin', 'Admin', 'Nhân viên'] as $name) {
            $typeIds[] = DB::table('type_accounts')->insertGetId(['name' => $name, 'created_at' => now(), 'updated_at' => now()]);
        }

        // 5. Chi nhánh (branches)
        $branchIds = [];
        $branches = [
            ['name' => 'Chi nhánh Mỹ Đình Hà Nội', 'address' => 'Mỹ Đình, Nam Từ Liêm, Hà Nội', 'phone' => '0901111111'],
            ['name' => 'Chi nhánh Tây Mỗ Hà Nội',  'address' => 'Tây Mỗ, Nam Từ Liêm, Hà Nội',  'phone' => '0902222222'],
        ];
        foreach ($branches as $b) {
            $branchIds[] = DB::table('branches')->insertGetId(array_merge($b, ['status' => 0, 'created_at' => now(), 'updated_at' => now()]));
        }

        // ===================== ACCOUNTS SUPER ADMIN & MANAGERS =====================

        DB::table('users')->insert([
            [
                'name'              => 'Mạnh',
                'email'             => 'manh@gmail.com',
                'password'          => Hash::make('12345678'),
                'email_verified_at' => now(),
                'status'            => 1,
                'position_id'       => $positionIds[2], // Giám đốc
                'type_accounts_id'  => $typeIds[0], // Super Admin
                'branch_id'         => null, // Super Admin có thể truy cập toàn bộ hệ thống
                'created_at'        => now(),
                'updated_at'        => now(),
            ],
            [
                'name'              => 'Thành',
                'email'             => 'thanh@gmail.com',
                'password'          => Hash::make('12345678'),
                'email_verified_at' => now(),
                'status'            => 1,
                'position_id'       => $positionIds[1], // Quản lý chi nhánh
                'type_accounts_id'  => $typeIds[1], // Admin
                'branch_id'         => $branchIds[0], // Chi nhánh Mỹ Đình
                'created_at'        => now(),
                'updated_at'        => now(),
            ],
            [
                'name'              => 'Hùng',
                'email'             => 'hung@gmail.com',
                'password'          => Hash::make('12345678'),
                'email_verified_at' => now(),
                'status'            => 1,
                'position_id'       => $positionIds[1], // Quản lý chi nhánh
                'type_accounts_id'  => $typeIds[1], // Admin
                'branch_id'         => $branchIds[1], // Chi nhánh Tây Mỗ
                'created_at'        => now(),
                'updated_at'        => now(),
            ]
        ]);

        $this->command->info('✅ Seed xong: ' . count($partIds) . ' bộ phận, ' . count($positionIds) . ' vị trí, ' . count($teamIds) . ' đội nhóm, ' . count($branchIds) . ' chi nhánh, 3 users (1 Super Admin, 2 Managers)');
    }
}
