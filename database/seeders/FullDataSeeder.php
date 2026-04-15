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

        // ===================== ACCOUNTS SUPER ADMIN =====================

        DB::table('users')->insert([
            [
                'name'              => 'Mạnh',
                'email'             => 'manh@gmail.com',
                'password'          => Hash::make('12345678'),
                'email_verified_at' => now(),
                'status'            => 1,
                'part_id'           => $partIds[0], // Bất kì
                'position_id'       => $positionIds[2], // Giám đốc
                'type_accounts_id'  => $typeIds[0], // Super Admin
                'branch_id'         => $branchIds[0], // Chi nhánh Mỹ Đình
                'created_at'        => now(),
                'updated_at'        => now(),
            ],
            [
                'name'              => 'Thành',
                'email'             => 'thanh@gmail.com',
                'password'          => Hash::make('12345678'),
                'email_verified_at' => now(),
                'status'            => 1,
                'part_id'           => $partIds[0], // Bất kì
                'position_id'       => $positionIds[2], // Giám đốc
                'type_accounts_id'  => $typeIds[0], // Super Admin
                'branch_id'         => $branchIds[1], // Chi nhánh Tây Mỗ
                'created_at'        => now(),
                'updated_at'        => now(),
            ]
        ]);

        // ===================== 50 USERS =====================

        $firstNames = ['Nguyễn', 'Trần', 'Lê', 'Phạm', 'Hoàng', 'Huỳnh', 'Phan', 'Vũ', 'Võ', 'Đặng', 'Bùi', 'Đỗ', 'Hồ', 'Ngô', 'Dương'];
        $midMale   = ['Văn', 'Hữu', 'Minh', 'Quốc', 'Đức', 'Thành', 'Bảo', 'Trung', 'Tuấn', 'Anh'];
        $midFemale = ['Thị', 'Ngọc', 'Thanh', 'Kim', 'Mỹ', 'Thùy', 'Phương', 'Lan', 'Thu', 'Hồng'];
        $lastMale  = ['Nam', 'Hùng', 'Dũng', 'Tùng', 'Phong', 'Khoa', 'Hải', 'Long', 'Bình', 'Cường', 'Tú', 'Kiên', 'Mạnh'];
        $lastFemale= ['Hoa', 'Lan', 'Mai', 'Linh', 'Ngân', 'Thảo', 'Trang', 'Yến', 'Nhi', 'Chi', 'Vân', 'Vy', 'My'];

        $addresses = [
            '12 Lê Lợi, Q.1, TP.HCM', '34 Trần Phú, Ba Đình, Hà Nội',
            '56 Nguyễn Trãi, Thanh Xuân, Hà Nội', '78 Lý Thường Kiệt, Q.10, TP.HCM',
            '90 Hai Bà Trưng, Hoàn Kiếm, Hà Nội', '102 Điện Biên Phủ, Q.3, TP.HCM',
            '15 Phan Châu Trinh, Hải Châu, Đà Nẵng', '27 Bạch Đằng, Hải Phòng',
            '33 Tô Hiệu, Lê Chân, Hải Phòng', '44 Nguyễn Huệ, Ninh Kiều, Cần Thơ',
        ];

        $phones = [];
        for ($i = 0; $i < 60; $i++) {
            $phones[] = '09' . rand(10000000, 99999999);
        }

        $users = [];
        for ($i = 1; $i <= 50; $i++) {
            $sex    = rand(0, 1);
            $fn     = $firstNames[array_rand($firstNames)];
            $mid    = $sex == 0 ? $midMale[array_rand($midMale)] : $midFemale[array_rand($midFemale)];
            $last   = $sex == 0 ? $lastMale[array_rand($lastMale)] : $lastFemale[array_rand($lastFemale)];
            $name   = "$fn $mid $last";
            $email  = 'user' . str_pad($i, 3, '0', STR_PAD_LEFT) . '@cafe.com';
            $status = rand(0, 4) === 0 ? 1 : 0; // ~20% nghỉ việc

            $users[] = [
                'name'              => $name,
                'email'             => $email,
                'password'          => Hash::make('password'),
                'email_verified_at' => now(),
                'birthday'          => Carbon::now()->subYears(rand(20, 45))->subDays(rand(0, 365))->format('Y-m-d'),
                'sex'               => $sex,
                'part_id'           => $partIds[array_rand($partIds)],
                'position_id'       => $positionIds[array_rand($positionIds)],
                'type_work'         => rand(0, 1),
                'team_id'           => $teamIds[array_rand($teamIds)],
                'phone'             => $phones[$i],
                'address'           => $addresses[array_rand($addresses)],
                'status'            => $status,
                'start_day'         => Carbon::now()->subMonths(rand(1, 36))->format('Y-m-d H:i:s'),
                'end_day'           => $status == 1 ? Carbon::now()->subDays(rand(1, 90))->format('Y-m-d H:i:s') : null,
                'type_accounts_id'  => $typeIds[array_rand($typeIds)],
                'branch_id'         => $branchIds[array_rand($branchIds)],
                'created_at'        => now(),
                'updated_at'        => now(),
            ];
        }

        // Insert theo batch để tránh lỗi email trùng
        DB::table('users')->insert($users);

        $this->command->info('✅ Seed xong: ' . count($partIds) . ' bộ phận, ' . count($positionIds) . ' vị trí, ' . count($teamIds) . ' đội nhóm, ' . count($branchIds) . ' chi nhánh, 50 users');
    }
}
