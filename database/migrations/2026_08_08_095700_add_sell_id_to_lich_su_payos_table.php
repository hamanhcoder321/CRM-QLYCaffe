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
        Schema::table('lich_su_payos', function (Blueprint $table) {
            if (!Schema::hasColumn('lich_su_payos', 'sell_id')) {
                $table->integer('sell_id')->nullable()->after('so_thang');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lich_su_payos', function (Blueprint $table) {
            if (Schema::hasColumn('lich_su_payos', 'sell_id')) {
                $table->dropColumn('sell_id');
            }
        });
    }
};
