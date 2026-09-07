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
        Schema::create('lead_reminders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lead_id')->constrained()->onDelete('cascade');
            $table->foreignId('counselor_id')->constrained('users')->onDelete('cascade');
            $table->dateTime('reminder_date');
            $table->string('title');
            $table->text('notes')->nullable();
            $table->enum('status', ['pending', 'completed', 'snoozed'])->default('pending');
            $table->dateTime('snoozed_until')->nullable();
            $table->timestamps();

            $table->index(['lead_id', 'reminder_date']);
            $table->index('counselor_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lead_reminders');
    }
};
