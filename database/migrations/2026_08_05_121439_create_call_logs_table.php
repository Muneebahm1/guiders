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
        Schema::create('call_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lead_id')->constrained()->onDelete('cascade');
            $table->foreignId('counselor_id')->constrained('users')->onDelete('cascade');
            $table->dateTime('call_date');
            $table->integer('duration_seconds')->nullable();
            $table->enum('outcome', ['completed', 'no_answer', 'voicemail', 'busy', 'failed'])->default('completed');
            $table->text('notes')->nullable();
            $table->string('recording_url')->nullable();
            $table->timestamps();

            $table->index(['lead_id', 'call_date']);
            $table->index('counselor_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('call_logs');
    }
};
