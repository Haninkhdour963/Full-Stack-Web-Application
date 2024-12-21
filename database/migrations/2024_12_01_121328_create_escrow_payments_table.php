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
        Schema::create('escrow_payments', function (Blueprint $table) {
            $table->id();
        $table->foreignId('job_id')->constrained('job_postings');
        $table->foreignId('client_id')->constrained('users');
        $table->foreignId('technician_id')->constrained('technicians');
        $table->decimal('amount_min', 10, 2);
        $table->decimal('amount_max', 10, 2);
        $table->enum('status', ['hold', 'released', 'refunded', 'disputed']);
        $table->timestamps();
        $table->softDeletes();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('escrow_payments');
    }
};
