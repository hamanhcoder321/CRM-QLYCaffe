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
        Schema::create('sells', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shipment_id')->nullable()->constrained('shipments')->cascadeOnUpdate();
            $table->unsignedTinyInteger('status')->default(0)->comment('0: chưa bán, 1: đã bán, 2, lưu kho');
            $table->string('name')->nullable();
            $table->integer('shipment_revenue');
            $table->integer('profit');
            $table->unsignedTinyInteger('storage')->default(0)->comment('0: không, 1: có');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sells');
    }
};
