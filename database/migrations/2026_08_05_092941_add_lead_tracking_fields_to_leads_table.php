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
            $table->boolean('highly_interested')->default(false)->after('remarks');
            $table->json('call_log')->nullable()->after('highly_interested');
            $table->integer('messages')->default(0)->after('call_log');
            $table->integer('visits')->default(0)->after('messages');
            $table->string('funnel_stage')->default('Lead')->after('visits');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropColumn(['highly_interested', 'call_log', 'messages', 'visits', 'funnel_stage']);
        });
    }
};
