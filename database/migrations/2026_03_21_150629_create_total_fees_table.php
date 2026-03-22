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
        Schema::create('total_fees', function (Blueprint $table) {
            $table->id();
            $table->date('day')->nullable();
            $table->string('content')->nullable();
            $table->integer('money')->nullable();
            $table->foreignId('type_fee_id')->nullable()->constrained('type_fees')->cascadeOnUpdate();
            $table->foreignId('atm_id')->nullable()->constrained('atms')->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('total_fees');
    }
};
