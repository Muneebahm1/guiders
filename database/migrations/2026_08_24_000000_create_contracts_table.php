<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('created_by_id')->constrained('users');
            $table->string('guardian_name')->nullable();
            $table->string('cnic')->nullable();
            $table->string('phone')->nullable();
            $table->string('country')->nullable();
            $table->string('university')->nullable();
            $table->boolean('university_tbd')->default(false);
            $table->string('course')->nullable();
            $table->string('service_type')->nullable();
            $table->text('service_detail');
            $table->string('intake')->nullable();
            $table->decimal('total_fee', 12, 2)->nullable();
            $table->string('currency')->default('PKR');
            $table->json('clauses')->nullable();
            $table->text('custom_terms')->nullable();
            $table->json('requirements')->nullable();
            $table->timestamps();
            $table->index('created_by_id');
        });

        Schema::create('contract_installments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contract_id')->constrained()->cascadeOnDelete();
            $table->string('description')->nullable();
            $table->decimal('amount', 12, 2)->default(0);
            $table->date('due_date')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contract_installments');
        Schema::dropIfExists('contracts');
    }
};
