<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->text('suggestion')->nullable()->after('city');
            $table->text('remarks')->nullable()->after('suggestion');
        });

        Schema::table('follow_ups', function (Blueprint $table) {
            $table->dropColumn(['suggestion', 'remarks']);
        });
    }

    public function down(): void
    {
        Schema::table('follow_ups', function (Blueprint $table) {
            $table->text('suggestion')->nullable()->after('due_date');
            $table->text('remarks')->nullable()->after('suggestion');
        });

        Schema::table('leads', function (Blueprint $table) {
            $table->dropColumn(['suggestion', 'remarks']);
        });
    }
};
