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
        Schema::create('job_bids', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_id')->constrained('job_postings');
            $table->foreignId('technician_id')->constrained('technicians');
            $table->decimal('bid_amount', 10, 2);
            $table->text('bid_message')->nullable();
            $table->enum('status', ['pending', 'accepted', 'declined', 'withdrawn']);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_bids');
    }
};
