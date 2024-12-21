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
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->enum('user_role', ['admin', 'client', 'technician'])->default('client'); // Add user_role enum
            $table->rememberToken();
            $table->timestamps(0);
            $table->string('profile_image', 255)->nullable()->comment('Optional user profile picture');
           $table->string('phone_number', 20);
           $table->string('mobile_phone', 20)->nullable()->comment('Mobile phone number');
           $table->string('location', 255)->nullable()->comment('User location');
          $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('deleted_at');
        });
    }
};
