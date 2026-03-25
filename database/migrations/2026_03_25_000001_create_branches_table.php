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
        Schema::create('branches', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable()->comment('Tên chi nhánh');
            $table->string('address')->nullable()->comment('Địa chỉ chi nhánh');
            $table->string('phone')->nullable()->comment('Số điện thoại');
            $table->foreignId('manager_id')->nullable()->constrained('users')->cascadeOnUpdate()->comment('Quản lý chi nhánh');
            $table->unsignedTinyInteger('status')->default(0)->comment('0: đang hoạt động, 1: tạm đóng');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('branches');
    }
};
