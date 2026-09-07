<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('opportunities', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('country')->nullable();
            $table->enum('type', ['study_abroad', 'research_publication'])->default('study_abroad');
            $table->date('deadline');
            $table->text('requirements')->nullable();
            $table->string('official_link')->nullable();
            $table->string('application_link')->nullable();
            $table->decimal('cost', 10, 2)->default(0);
            $table->string('currency', 3)->default('USD');
            $table->text('guidance')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('opportunities');
    }
};
