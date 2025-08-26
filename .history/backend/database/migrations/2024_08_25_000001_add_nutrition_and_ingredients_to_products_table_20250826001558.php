<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'nutrition_info')) {
                // 營養資訊 JSON 欄位
                $table->json('nutrition_info')->nullable()->after('description');
            }
            if (!Schema::hasColumn('products', 'ingredients')) {
                // 成分資訊
                $table->text('ingredients')->nullable()->after('nutrition_info');
            }
            if (!Schema::hasColumn('products', 'allergens')) {
                $table->text('allergens')->nullable()->after('ingredients');
            }
            if (!Schema::hasColumn('products', 'origin')) {
                $table->string('origin')->default('台灣')->after('allergens');
            }
            if (!Schema::hasColumn('products', 'package_info')) {
                // 包裝資訊 JSON 欄位
                $table->json('package_info')->nullable()->after('origin');
            }
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['nutrition_info', 'ingredients', 'allergens', 'origin', 'package_info']);
        });
    }
};
