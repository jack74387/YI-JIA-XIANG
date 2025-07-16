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
        Schema::table('orders', function (Blueprint $table) {
            // 門市自取相關欄位
            $table->string('store_id')->nullable()->after('shipping_method');
            $table->string('store_name')->nullable()->after('store_id');
            $table->string('store_address')->nullable()->after('store_name');
            $table->string('store_phone')->nullable()->after('store_address');
            $table->string('store_hours')->nullable()->after('store_phone');
            
            // 超商取貨相關欄位
            $table->string('convenience_store_name')->nullable()->after('store_hours');
            $table->string('convenience_store_address')->nullable()->after('convenience_store_name');
            $table->string('convenience_store_phone')->nullable()->after('convenience_store_address');
            $table->string('convenience_store_chain')->nullable()->after('convenience_store_phone');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'store_id',
                'store_name', 
                'store_address',
                'store_phone',
                'store_hours',
                'convenience_store_name',
                'convenience_store_address',
                'convenience_store_phone',
                'convenience_store_chain'
            ]);
        });
    }
};
