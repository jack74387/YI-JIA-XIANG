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
        Schema::table('point_transactions', function (Blueprint $table) {
            if (!Schema::hasColumn('point_transactions', 'admin_id')) {
                $table->integer('admin_id')->nullable()->after('id');
            }
            $table->enum('type', ['earn', 'spend', 'expire', 'adjust'])->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('point_transactions', function (Blueprint $table) {
            // if (Schema::hasColumn('point_transactions', 'admin_id')) {
            //     $table->dropColumn('admin_id');
            // }
            $table->enum('type', ['earn', 'spend', 'expire'])->change();
        });
    }
};
