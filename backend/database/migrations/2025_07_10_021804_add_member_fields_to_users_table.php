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
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->nullable()->after('email');
            $table->text('address')->nullable()->after('phone');
            $table->date('birthday')->nullable()->after('address');
            $table->enum('gender', ['male', 'female', 'other'])->nullable()->after('birthday');
            $table->integer('points')->default(0)->after('gender');
            $table->enum('member_level', ['bronze', 'silver', 'gold', 'platinum'])->default('bronze')->after('points');
            $table->string('avatar')->nullable()->after('member_level');
            $table->boolean('email_notifications')->default(true)->after('avatar');
            $table->boolean('sms_notifications')->default(false)->after('email_notifications');
            $table->timestamp('last_login_at')->nullable()->after('sms_notifications');
            $table->string('line_user_id')->nullable()->after('last_login_at');
            $table->string('facebook_user_id')->nullable()->after('line_user_id');
            $table->string('google_user_id')->nullable()->after('facebook_user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'phone',
                'address',
                'birthday',
                'gender',
                'points',
                'member_level',
                'avatar',
                'email_notifications',
                'sms_notifications',
                'last_login_at',
                'line_user_id',
                'facebook_user_id',
                'google_user_id'
            ]);
        });
    }
};
