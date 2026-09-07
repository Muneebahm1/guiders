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
        Schema::table('leads', function (Blueprint $table) {
            $table->boolean('contract_signed')->default(false)->after('funnel_stage');
            $table->date('contract_date')->nullable()->after('contract_signed');
            $table->boolean('payment_done')->default(false)->after('contract_date');
            $table->date('payment_date')->nullable()->after('payment_done');
            $table->string('currency')->default('USD')->after('payment_date');
            $table->string('english_test')->default('None')->after('currency');
            $table->string('english_score')->nullable()->after('english_test');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropColumn(['contract_signed', 'contract_date', 'payment_done', 'payment_date', 'currency', 'english_test', 'english_score']);
        });
    }
};
