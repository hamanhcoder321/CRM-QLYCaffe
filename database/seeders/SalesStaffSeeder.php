<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SalesStaffSeeder extends Seeder
{
    public function run(): void
    {
        $nhanVien = [
            ['name' => 'Nguyen Thi Mai Anh',  'email' => 'sale001@cafe.com', 'phone' => '0901111001', 'branch_id' => 1],
            ['name' => 'Tran Van Hung',        'email' => 'sale002@cafe.com', 'phone' => '0901111002', 'branch_id' => 2],
            ['name' => 'Le Thi Huong',         'email' => 'sale003@cafe.com', 'phone' => '0901111003', 'branch_id' => 3],
            ['name' => 'Pham Duc Minh',        'email' => 'sale004@cafe.com', 'phone' => '0901111004', 'branch_id' => 4],
            ['name' => 'Hoang Thi Lan Anh',    'email' => 'sale005@cafe.com', 'phone' => '0901111005', 'branch_id' => 5],
            ['name' => 'Vu Quang Huy',         'email' => 'sale006@cafe.com', 'phone' => '0901111006', 'branch_id' => 1],
            ['name' => 'Do Thi Thanh Thuy',    'email' => 'sale007@cafe.com', 'phone' => '0901111007', 'branch_id' => 2],
            ['name' => 'Bui Van Tung',         'email' => 'sale008@cafe.com', 'phone' => '0901111008', 'branch_id' => 3],
            ['name' => 'Ngo Thi Bich Ngoc',   'email' => 'sale009@cafe.com', 'phone' => '0901111009', 'branch_id' => 4],
            ['name' => 'Dinh Cong Son',        'email' => 'sale010@cafe.com', 'phone' => '0901111010', 'branch_id' => 5],
        ];

        $password = Hash::make('12345678');
        $now = now();

        foreach ($nhanVien as $nv) {
            if (DB::table('users')->where('email', $nv['email'])->exists()) {
                $this->command->line("  Skip: {$nv['email']}");
                continue;
            }

            DB::table('users')->insert([
                'name'              => $nv['name'],
                'email'             => $nv['email'],
                'password'          => $password,
                'email_verified_at' => $now,
                'type_accounts_id'  => 3,
                'part_id'           => 3,
                'position_id'       => 1,
                'team_id'           => 5,
                'branch_id'         => $nv['branch_id'],
                'phone'             => $nv['phone'],
                'status'            => 0,
                'created_at'        => $now,
                'updated_at'        => $now,
            ]);

            $this->command->info("  OK: {$nv['name']} ({$nv['email']}) - Branch #{$nv['branch_id']}");
        }

        $this->command->info("DONE! Password: 12345678");
    }
}
