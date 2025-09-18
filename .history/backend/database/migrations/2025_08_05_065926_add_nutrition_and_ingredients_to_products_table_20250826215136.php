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
            // 營養資訊欄位
            if (!Schema::hasColumn('products', 'nutrition_info')) {
                $table->json('nutrition_info')->nullable()->after('spec')->comment('營養資訊JSON格式');
            }
            
            // 成分資訊
            if (!Schema::hasColumn('products', 'ingredients')) {
                $table->text('ingredients')->nullable()->after('nutrition_info')->comment('主要成分');
            }
            if (!Schema::hasColumn('products', 'allergens')) {
                $table->text('allergens')->nullable()->after('ingredients')->comment('過敏原資訊');
            }
            
            // 保存相關資訊
            if (!Schema::hasColumn('products', 'shelf_life')) {
                $table->string('shelf_life')->nullable()->after('allergens')->comment('保存期限');
            }
            if (!Schema::hasColumn('products', 'storage_instructions')) {
                $table->text('storage_instructions')->nullable()->after('shelf_life')->comment('保存方式');
            }
            if (!Schema::hasColumn('products', 'origin')) {
                $table->string('origin')->nullable()->after('storage_instructions')->comment('產地');
            }
            
            // 包裝資訊
            if (!Schema::hasColumn('products', 'package_info')) {
                $table->json('package_info')->nullable()->after('origin')->comment('包裝規格資訊');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'nutrition_info',
                'ingredients', 
                'allergens',
                'shelf_life',
                'storage_instructions',
                'origin',
                'package_info'
            ]);
        });
    }
};
