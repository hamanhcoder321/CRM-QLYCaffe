<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            FullDataSeeder::class,
            NhapHangSeeder::class,
            MenuSeeder::class,
            NhaCungCapSeeder::class,
        ]);
    }
}
