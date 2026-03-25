<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Thêm branch_id vào các bảng: users, sells, storages, total_fees, arranges
     */
    public function up(): void
    {
        // 1. users -> nhân viên thuộc chi nhánh nào
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('branch_id')
                ->nullable()
                ->after('type_accounts_id')
                ->constrained('branches')
                ->cascadeOnUpdate()
                ->comment('Chi nhánh của nhân viên');
        });

        // 2. sells -> đơn bán thuộc chi nhánh nào
        Schema::table('sells', function (Blueprint $table) {
            $table->foreignId('branch_id')
                ->nullable()
                ->after('shipment_id')
                ->constrained('branches')
                ->cascadeOnUpdate()
                ->comment('Chi nhánh của đơn bán');
        });

        // 3. storages -> kho thuộc chi nhánh nào
        Schema::table('storages', function (Blueprint $table) {
            $table->foreignId('branch_id')
                ->nullable()
                ->after('shipment_id')
                ->constrained('branches')
                ->cascadeOnUpdate()
                ->comment('Chi nhánh của kho');
        });

        // 4. total_fees -> chi phí thuộc chi nhánh nào
        Schema::table('total_fees', function (Blueprint $table) {
            $table->foreignId('branch_id')
                ->nullable()
                ->after('atm_id')
                ->constrained('branches')
                ->cascadeOnUpdate()
                ->comment('Chi nhánh phát sinh chi phí');
        });

        // 5. arranges -> lô hàng thuộc chi nhánh nào
        Schema::table('arranges', function (Blueprint $table) {
            $table->foreignId('branch_id')
                ->nullable()
                ->after('total_arrange')
                ->constrained('branches')
                ->cascadeOnUpdate()
                ->comment('Chi nhánh của lô hàng');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('arranges', function (Blueprint $table) {
            $table->dropForeign(['branch_id']);
            $table->dropColumn('branch_id');
        });

        Schema::table('total_fees', function (Blueprint $table) {
            $table->dropForeign(['branch_id']);
            $table->dropColumn('branch_id');
        });

        Schema::table('storages', function (Blueprint $table) {
            $table->dropForeign(['branch_id']);
            $table->dropColumn('branch_id');
        });

        Schema::table('sells', function (Blueprint $table) {
            $table->dropForeign(['branch_id']);
            $table->dropColumn('branch_id');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['branch_id']);
            $table->dropColumn('branch_id');
        });
    }
};
