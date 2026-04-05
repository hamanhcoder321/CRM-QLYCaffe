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
        // ===================== LOOKUP TABLES =====================

        // 1. Bộ phận (parts)
        $parts = DB::table('parts')->insertGetId(['name' => 'Kho', 'created_at' => now(), 'updated_at' => now()]);
        $partIds = [];
        foreach ([
            'Kho vận', 'Kinh doanh', 'Kế toán', 'Nhân sự', 'Marketing', 'Vận hành', 'IT', 'Bảo vệ'
        ] as $name) {
            $partIds[] = DB::table('parts')->insertGetId(['name' => $name, 'created_at' => now(), 'updated_at' => now()]);
        }
        $partIds[] = $parts;

        // 2. Vị trí (positions)
        $positionIds = [];
        foreach ([
            'Nhân viên', 'Trưởng nhóm', 'Trưởng bộ phận', 'Phó giám đốc', 'Giám đốc', 'Thực tập sinh', 'Cố vấn'
        ] as $name) {
            $positionIds[] = DB::table('positions')->insertGetId(['name' => $name, 'created_at' => now(), 'updated_at' => now()]);
        }

        // 3. Đội nhóm (teams)
        $teamIds = [];
        foreach ([
            'Team A', 'Team B', 'Team C', 'Team Leader', 'Team Sale', 'Team Ops'
        ] as $name) {
            $teamIds[] = DB::table('teams')->insertGetId(['name' => $name, 'created_at' => now(), 'updated_at' => now()]);
        }

        // 4. Loại tài khoản (type_accounts)
        $typeIds = [];
        foreach (['Admin', 'Quản lý', 'Nhân viên', 'Khách'] as $name) {
            $typeIds[] = DB::table('type_accounts')->insertGetId(['name' => $name, 'created_at' => now(), 'updated_at' => now()]);
        }

        // 5. Chi nhánh (branches) — không cần manager_id ban đầu
        $branchIds = [];
        $branches = [
            ['name' => 'Chi nhánh Hà Nội',    'address' => '45 Lý Thái Tổ, Hoàn Kiếm, Hà Nội',      'phone' => '0901111111'],
            ['name' => 'Chi nhánh TP.HCM',     'address' => '128 Nguyễn Văn Linh, Q.7, TP.HCM',      'phone' => '0902222222'],
            ['name' => 'Chi nhánh Đà Nẵng',    'address' => '56 Bạch Đằng, Hải Châu, Đà Nẵng',       'phone' => '0903333333'],
            ['name' => 'Chi nhánh Hải Phòng',  'address' => 'Quốc lộ 5, An Dương, Hải Phòng',        'phone' => '0904444444'],
            ['name' => 'Chi nhánh Cần Thơ',    'address' => '99 Trần Hưng Đạo, Ninh Kiều, Cần Thơ', 'phone' => '0905555555'],
        ];
        foreach ($branches as $b) {
            $branchIds[] = DB::table('branches')->insertGetId(array_merge($b, ['status' => 0, 'created_at' => now(), 'updated_at' => now()]));
        }

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
