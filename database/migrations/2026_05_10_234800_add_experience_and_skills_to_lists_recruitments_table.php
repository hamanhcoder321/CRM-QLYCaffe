<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('lists_recruitments')) {
            Schema::table('lists_recruitments', function (Blueprint $table) {
                if (!Schema::hasColumn('lists_recruitments', 'experience')) {
                    $table->text('experience')->nullable()->comment('Kinh nghiệm làm việc');
                }
                if (!Schema::hasColumn('lists_recruitments', 'skills')) {
                    $table->text('skills')->nullable()->comment('Kỹ năng cá nhân');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('lists_recruitments')) {
            Schema::table('lists_recruitments', function (Blueprint $table) {
                if (Schema::hasColumn('lists_recruitments', 'experience')) {
                    $table->dropColumn('experience');
                }
                if (Schema::hasColumn('lists_recruitments', 'skills')) {
                    $table->dropColumn('skills');
                }
            });
        }
    }
};
