<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('follow_ups', function (Blueprint $table) {
            $table->foreignId('lead_id')->nullable()->after('counselor_id')->constrained('leads')->nullOnDelete();
            $table->text('suggestion')->nullable()->after('due_date');
            $table->text('remarks')->nullable()->after('suggestion');
            $table->dropColumn('notes');
        });
    }

    public function down(): void
    {
        Schema::table('follow_ups', function (Blueprint $table) {
            $table->string('notes')->nullable();
            $table->dropColumn(['suggestion', 'remarks']);
            $table->dropConstrainedForeignId('lead_id');
        });
    }
};
