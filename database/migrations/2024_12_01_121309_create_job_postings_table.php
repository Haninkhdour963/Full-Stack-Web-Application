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
        Schema::create('job_postings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained('users');
            $table->string('title', 255);
            $table->text('description');
            $table->foreignId('category_id')->constrained('categories');
            $table->string('location', 255)->nullable()->comment('Job location');
            $table->decimal('budget_min', 10, 2);
            $table->decimal('budget_max', 10, 2);
            $table->string('duration', 100)->nullable()->comment('Estimated duration of the job');
            $table->enum('status', ['open', 'in_progress', 'completed', 'cancelled', 'closed']);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_postings');
    }
};
