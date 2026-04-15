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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('email')->unique();
            $table->string('password');
            $table->rememberToken()->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->date('birthday')->nullable();
            $table->unsignedTinyInteger('sex')->default(0)->comment('0: nam, 1: nu');
            $table->foreignId('part_id')->nullable()->constrained('parts')->cascadeOnUpdate();
            $table->foreignId('position_id')->nullable()->constrained('positions')->cascadeOnUpdate();
            $table->unsignedTinyInteger('type_work')->default(0)->comment('0: fullname, 1: parttime');
            $table->foreignId('team_id')->nullable()->constrained('teams')->cascadeOnUpdate();
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->unsignedTinyInteger('status')->default(0)->comment('0: đang làm, 1: nghỉ viec');
            $table->dateTime('start_day')->nullable();
            $table->dateTime('end_day')->nullable();
            $table->foreignId('type_accounts_id')->nullable()->constrained('type_accounts')->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
