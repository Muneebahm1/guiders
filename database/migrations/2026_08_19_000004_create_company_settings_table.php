<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('company_settings', function (Blueprint $table) {
            $table->id();
            $table->string('company_name')->default('THE GUIDERS');
            $table->text('address')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('account_number')->nullable();
            $table->string('branch_code')->nullable();
            $table->string('iban')->nullable();
            $table->text('notes')->nullable();
            $table->text('terms')->nullable();
            $table->foreignId('updated_by_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        DB::table('company_settings')->insert([
            'company_name' => 'THE GUIDERS',
            'address' => 'Plot no 44 UBL business Park Gulberg Greens Islamabad',
            'bank_name' => 'UNITED BANK LIMITED',
            'account_number' => '356485762',
            'branch_code' => '0446',
            'iban' => 'PK98UNIL0109000356485762',
            'notes' => "The Guiders Overseas Educational Services Private limited",
            'terms' => 'The deposit/payment must be transferred only to the company\'s designated bank account. After completing the payment, kindly share the payment receipt for verification and confirmation.',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('company_settings');
    }
};
