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
        Schema::table('timesheets', function (Blueprint $table) {
            $table->string('shift')->nullable()->after('hour'); // Ca làm việc: Sáng, Chiều, Tối, Fulltime
            $table->text('note')->nullable()->after('shift'); // Ghi chú, Trách nhiệm quản lý
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('timesheets', function (Blueprint $table) {
            $table->dropColumn(['shift', 'note']);
        });
    }
};
