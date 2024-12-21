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
        Schema::create('disputes', function (Blueprint $table) {
        $table->id();
        $table->foreignId('job_id')->constrained('job_postings');
        $table->foreignId('initiator_id')->constrained('users');
        $table->foreignId('technician_id')->constrained('technicians');
        $table->foreignId('client_id')->constrained('users');
        $table->text('dispute_reason');
        $table->enum('status', ['open', 'resolved', 'cancelled']);
        $table->timestamps();
        $table->softDeletes();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('disputes');
    }
};
