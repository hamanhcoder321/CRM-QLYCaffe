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
        // Danh sách các bảng cần bổ sung branch_id
        $tables = [
            'products',
            'drinks',
            'timesheets',
            'recruitments',
            'salary_mechanism',
            'lists_recruitments',
            'care_customers'
        ];

        foreach ($tables as $tableName) {
            if (Schema::hasTable($tableName) && !Schema::hasColumn($tableName, 'branch_id')) {
                Schema::table($tableName, function (Blueprint $table) {
                    $table->foreignId('branch_id')
                        ->nullable()
                        ->constrained('branches')
                        ->cascadeOnUpdate()
                        ->comment('Chi nhánh quản lý dữ liệu này');
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = [
            'products',
            'drinks',
            'timesheets',
            'recruitments',
            'salary_mechanism',
            'lists_recruitments',
            'care_customers'
        ];

        foreach ($tables as $tableName) {
            if (Schema::hasTable($tableName) && Schema::hasColumn($tableName, 'branch_id')) {
                Schema::table($tableName, function (Blueprint $table) {
                    $table->dropForeign(['branch_id']);
                    $table->dropColumn('branch_id');
                });
            }
        }
    }
};
