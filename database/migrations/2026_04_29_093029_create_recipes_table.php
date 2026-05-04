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
        Schema::create('recipes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('drink_id');
            $table->unsignedBigInteger('product_id')->comment('Trỏ tới bảng products (Nguyên liệu)');
            $table->integer('quantity')->default(1)->comment('Định mức tiêu hao');
            $table->timestamps();

            $table->foreign('drink_id')->references('id')->on('drinks')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recipes');
    }
};
