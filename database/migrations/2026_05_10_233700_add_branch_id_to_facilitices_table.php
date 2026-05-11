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
        if (Schema::hasTable('_facilitices') && !Schema::hasColumn('_facilitices', 'branch_id')) {
            Schema::table('_facilitices', function (Blueprint $table) {
                $table->foreignId('branch_id')
                    ->nullable()
                    ->constrained('branches')
                    ->cascadeOnUpdate()
                    ->comment('Chi nhánh quản lý dữ liệu này');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('_facilitices') && Schema::hasColumn('_facilitices', 'branch_id')) {
            Schema::table('_facilitices', function (Blueprint $table) {
                $table->dropForeign(['branch_id']);
                $table->dropColumn('branch_id');
            });
        }
    }
};
