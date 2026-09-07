<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->string('email')->nullable()->after('contact');
        });

        // Backfill: existing rows that stored an email address in `contact` get it copied
        // into the new dedicated `email` column (contact is left untouched).
        DB::table('leads')
            ->whereNotNull('contact')
            ->where('contact', 'like', '%@%')
            ->update(['email' => DB::raw('contact')]);
    }

    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropColumn('email');
        });
    }
};
