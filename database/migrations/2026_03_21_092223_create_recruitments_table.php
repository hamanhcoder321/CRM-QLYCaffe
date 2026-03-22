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
        Schema::create('recruitments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('part_id')->constrained('parts')->cascadeOnUpdate()->nullable();
            $table->foreignId('position_id')->constrained('positions')->cascadeOnUpdate()->nullable();
            $table->integer('number');
            $table->unsignedTinyInteger('prioritize')->default('0')->comment('0: thap, 1: trung binh, 2: cao');
            $table->dateTime('deadline')->nullable();
            $table->foreignId('user_id')->constrained('users')->cascadeOnUpdate()->nullable();
            $table->string('social')->nullable();
            $table->unsignedTinyInteger('result')->default('0')->comment('0: khong dat, 1: dat');
            $table->unsignedTinyInteger('status')->default('0')->comment('0: đang tuyển, 1: hoàn thành, 2: trễ');
            $table->string('obstacle')->nullable();
            $table->string('solution')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recruitments');
    }
};
