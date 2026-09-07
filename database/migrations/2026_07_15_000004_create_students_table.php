<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('counselor_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('opportunity_id')->nullable()->constrained('opportunities')->nullOnDelete();
            $table->string('name');
            $table->enum('app_stage', ['Not Applied', 'Submitted', 'Waiting for Acceptance', 'Accepted', 'Rejected'])->default('Not Applied');
            $table->date('fee_date')->nullable();
            $table->enum('visa_stage', ['Not Started', 'Fee Paid', 'File Preparation', 'Visa Submitted', 'Visa Approved', 'Visa Rejected'])->default('Not Started');
            $table->date('visa_date')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
