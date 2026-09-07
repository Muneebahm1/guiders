<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('invoice_items', function (Blueprint $table) {
            $table->renameColumn('rate', 'currency_value');
            $table->renameColumn('quantity', 'exchange_rate');
        });
    }

    public function down(): void
    {
        Schema::table('invoice_items', function (Blueprint $table) {
            $table->renameColumn('currency_value', 'rate');
            $table->renameColumn('exchange_rate', 'quantity');
        });
    }
};
