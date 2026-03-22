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
        Schema::create('lists_recruitments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('recruitment_id')->nullable()->constrained('recruitments')->cascadeOnUpdate();
            $table->dateTime('day')->nullable();
            $table->string('name')->nullable();
            $table->string('phone')->nullable();
            $table->date('birthday')->nullable();
            $table->unsignedTinyInteger('interview')->default(0)->comment('0: không, 1: có');
            $table->foreignId('user_id')->constrained('users')->cascadeOnUpdate()->nullable();
            $table->unsignedTinyInteger('result')->default(0)->comment('0: không đạt, 1: đạt');
            $table->dateTime('day_work')->nullable();
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lists_recruitments');
    }
};
