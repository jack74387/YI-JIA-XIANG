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
        Schema::create('product_spec_prices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_spec_id');
            $table->integer('price'); // 價格
            $table->string('label')->nullable(); // 價格說明（如特價、原價）
            $table->timestamps();

            $table->foreign('product_spec_id')->references('id')->on('product_specs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_spec_prices');
    }
};
