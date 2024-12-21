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
        Schema::create('technicians', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->integer('commercial_records')->nullable()->comment('For business documentation');
            $table->string('identity_number', 255);
            $table->text('skills')->nullable()->comment('Comma-separated list of skills');
            $table->decimal('hourly_rate', 10, 2);
            $table->text('certifications')->nullable()->comment('Comma-separated list of certifications');
            $table->text('bio')->nullable();
            $table->string('location', 255)->nullable()->comment('Technician location');
            $table->decimal('rating', 3, 2)->nullable()->comment('Average rating, updated from reviews');
            $table->dateTime('available_from');
            $table->timestamps();
            $table->softDeletes();
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('technicians');
    }
};
