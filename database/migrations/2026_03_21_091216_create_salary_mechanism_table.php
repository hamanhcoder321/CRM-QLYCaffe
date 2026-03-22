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
        Schema::create('salary_mechanism', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnUpdate()->nullable();
            $table->integer('salary')->nullable();
            $table->integer('ot_day')->nullable();
            $table->integer('ot_hour')->nullable();
            $table->integer('responsibility')->nullable();
            $table->integer('enthusiasm')->nullable();
            $table->integer('support')->nullable();
            $table->integer('salary_keep')->nullable();
            $table->integer('salary_need_keep')->nullable();
            $table->integer('month_keep')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salary_mechanism');
    }
};
