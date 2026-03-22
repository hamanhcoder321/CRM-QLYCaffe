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
        Schema::create('type_fees', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('code', 100)->nullable();
            $table->unsignedTinyInteger('type')->default(0)->comment('0: chi, 1: thu');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('type_fees');
    }
};
