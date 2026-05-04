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
        Schema::table('sell_products', function (Blueprint $table) {
            $table->unsignedBigInteger('drink_id')->nullable()->after('sell_id');
            // product_id is now nullable since a sell_product might refer to a drink instead
            $table->unsignedBigInteger('product_id')->nullable()->change();
            $table->foreign('drink_id')->references('id')->on('drinks')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sell_products', function (Blueprint $table) {
            $table->dropForeign(['drink_id']);
            $table->dropColumn('drink_id');
        });
    }
};
