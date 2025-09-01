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
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->id(); // bigint(20) UNSIGNED AUTO_INCREMENT
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Adds foreign key constraint
            $table->integer('scanned_count')->default(0)->comment('Number of times the user has scanned barcode');
            $table->integer('scan_points')->default(10)->index();
            $table->unsignedBigInteger('total_reward_points')->default(0);
            $table->boolean('allow_notifications')->default(0);
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->string('timeZoneName')->nullable();
            $table->string('city')->nullable();
            $table->string('country')->nullable();
            $table->string('address')->nullable();
            $table->string('contact')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->string('device')->nullable();
            $table->string('slug')->unique()->nullable();
            $table->string('about_me')->unique()->nullable();
            $table->unsignedBigInteger('app_opening')->nullable();
            $table->string('app_version', 20)->nullable();
            $table->boolean('start_earning')->default(0);
            $table->unsignedInteger('watch_earning')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_profiles');
    }
};
