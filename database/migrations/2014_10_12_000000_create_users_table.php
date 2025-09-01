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
        Schema::create('users', function (Blueprint $table) {
            $table->id(); // bigint(20) UNSIGNED AUTO_INCREMENT
            $table->string('role', 191)->default('user');
            $table->string('uuid')->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('email')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->string('avatar')->default('https://admin.Email Builder.com/uploads/users/default.png');
            $table->enum('auth_provider', ['google', 'facebook', 'apple', 'site'])->default('site');
            $table->enum('status', ['active', 'inactive', 'pending', 'disabled', 'waiting', 'closed', 'on_hold'])->default('pending');
            $table->rememberToken(); // varchar(100), nullable
            $table->string('referral')->nullable();
            $table->string('refer_by')->nullable();
            $table->integer('forgotToken')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->string('fcm_token')->nullable();
            $table->softDeletes(); // adds deleted_at column
            $table->string('type')->default('normal');
            $table->string('auth_code')->nullable();
            $table->string('is_2way_auth')->default('0');
            $table->text('google2fa_secret')->nullable();
            $table->integer('is_anonymous')->nullable();
            $table->unsignedBigInteger('merged_in_user')->nullable();
            $table->boolean('is_2fa_enabled')->default(false);
            $table->timestamp('last_active')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
