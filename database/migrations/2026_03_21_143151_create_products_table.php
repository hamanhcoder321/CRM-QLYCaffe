<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shipment_id')->nullable()->constrained('shipments')->cascadeOnUpdate();
            $table->string('name')->nullable();
            $table->integer('number_in')->default(0);
            $table->integer('number_out')->default(0);
            $table->integer('price')->default(0)->comment('Gia ban (dong)');
            $table->integer('cost_price')->default(0)->comment('Gia von / gia nhap (dong)');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
