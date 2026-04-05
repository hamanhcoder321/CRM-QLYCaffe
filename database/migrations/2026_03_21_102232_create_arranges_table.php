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
        Schema::create('arranges', function (Blueprint $table) {
            $table->id();
            $table->date('day')->nullable();
            $table->string('name_arrange')->nullable();
            $table->string('name_customer')->nullable();
            $table->string('address')->nullable();
            $table->string('phone_customer')->nullable();
            $table->string('name')->nullable();
            $table->foreignId('sale_user_id')->nullable()->constrained('users')->cascadeOnUpdate();
            $table->foreignId('part_id')->nullable()->constrained('parts')->cascadeOnUpdate();
            $table->foreignId('team_id')->nullable()->constrained('teams')->cascadeOnUpdate();
            $table->string('account_social')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->cascadeOnUpdate();
            $table->foreignId('support_user_id')->nullable()->constrained('users')->cascadeOnUpdate();
            $table->unsignedTinyInteger('type_arrange')->default(0)->comment('0: mới, 1: cũ');
            $table->unsignedTinyInteger('result')->default(0)->comment('0: chưa bốc hàng, 1: hoàn thành, 2: fail');
            $table->text('reason_fail')->nullable();
            $table->integer('total_arrange')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('arranges');
    }
};
