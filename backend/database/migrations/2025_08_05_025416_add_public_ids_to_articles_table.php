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
        Schema::table('articles', function (Blueprint $table) {
            $table->json('images_public_ids')->nullable()->after('images'); // 圖片 public_id 陣列
            $table->json('videos_public_ids')->nullable()->after('videos'); // 影片 public_id 陣列
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->dropColumn(['images_public_ids', 'videos_public_ids']);
        });
    }
};
