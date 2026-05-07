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
        Schema::table('lists_recruitments', function (Blueprint $table) {
            $table->string('email')->nullable()->after('phone');
            $table->unsignedTinyInteger('status')->default(0)->after('result')->comment('0: Mới, 1: Đạt, 2: Không đạt');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lists_recruitments', function (Blueprint $table) {
            $table->dropColumn(['email', 'status']);
        });
    }
};
