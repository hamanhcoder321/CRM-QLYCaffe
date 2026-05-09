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
        Schema::create('lich_su_payos', function (Blueprint $table) {
            $table->id();
            $table->string('bin')->nullable();
            $table->integer('admin_id')->nullable();
            $table->string('accountNumber')->nullable();
            $table->string('accountName')->nullable();
            $table->decimal('amount', 20, 2)->nullable();
            $table->text('description')->nullable();
            $table->string('orderCode')->nullable();
            $table->string('currency')->nullable();
            $table->string('paymentLinkId')->nullable();
            $table->string('status')->nullable();
            $table->text('checkoutUrl')->nullable();
            $table->text('qrCode')->nullable();
            $table->string('hinh_thuc_thanh_toan')->nullable();
            $table->string('trang_thai')->nullable();
            $table->integer('article_id')->nullable();
            $table->string('customer_name')->nullable();
            $table->string('customer_phone')->nullable();
            $table->string('mothod_service')->nullable();
            $table->text('customer_note')->nullable();
            $table->integer('service_id')->nullable();
            $table->string('ma_don')->nullable();
            $table->string('loai_don')->nullable();
            $table->string('customer_email')->nullable();
            $table->decimal('so_tien', 20, 2)->nullable();
            $table->integer('cancel')->default(0);
            $table->string('payment_id')->nullable();
            $table->string('link')->nullable();
            $table->integer('shop_id')->nullable();
            $table->integer('so_thang')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lich_su_payos');
    }
};
