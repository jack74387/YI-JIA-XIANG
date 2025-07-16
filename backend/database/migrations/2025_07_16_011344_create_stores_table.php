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
        Schema::create('stores', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // 門市名稱
            $table->string('address'); // 門市地址
            $table->string('phone'); // 門市電話
            $table->string('hours')->nullable(); // 營業時間
            $table->text('map')->nullable(); // Google Maps 嵌入連結
            $table->text('map_link')->nullable(); // Google Maps 導航連結
            $table->boolean('is_active')->default(true); // 是否啟用
            $table->integer('sort_order')->default(0); // 排序順序
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stores');
    }
};
