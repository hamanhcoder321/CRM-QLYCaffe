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
            $table->unsignedTinyInteger('status')->default(0)->comment('0: chua ban, 1: da ban, 2: luu kho');
            $table->date('sell_day')->nullable();
            $table->string('name')->nullable();
            $table->integer('shipment_revenue')->default(0);
            $table->integer('profit')->default(0);
            $table->unsignedTinyInteger('storage')->default(0)->comment('0: khong, 1: co');
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
