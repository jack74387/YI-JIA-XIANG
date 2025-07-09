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
        Schema::table('products', function (Blueprint $table) {
            $table->integer('hot')->default(0)->after('price_small');
            $table->integer('views')->default(0)->after('hot');
            $table->string('subtitle')->nullable();
            $table->float('rating')->default(5);
            $table->integer('rating_count')->default(0);
            $table->integer('sold_count')->default(0);
            $table->integer('stock')->default(0);
            $table->json('images')->nullable();
            $table->text('spec')->nullable();
            $table->json('tags')->nullable();
            $table->json('recommend_ids')->nullable();
            $table->json('delivery')->nullable();
            $table->json('payment')->nullable();
            $table->integer('origin_price')->nullable();
            $table->string('weight')->nullable();
            $table->json('share_links')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'hot',
                'views',
                'subtitle',
                'rating',
                'rating_count',
                'sold_count',
                'stock',
                'images',
                'spec',
                'tags',
                'recommend_ids',
                'delivery',
                'payment',
                'origin_price',
                'weight',
                'share_links',
            ]);
        });
    }
};
