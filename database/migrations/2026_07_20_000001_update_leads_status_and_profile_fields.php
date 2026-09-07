<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("UPDATE leads SET status = 'New' WHERE status IN ('Contacted', 'Lost')");
        DB::statement("ALTER TABLE leads MODIFY COLUMN status ENUM('New', 'Call', 'Visit', 'Registered') DEFAULT 'New'");

        Schema::table('leads', function (Blueprint $table) {
            $table->string('desired_program')->nullable()->after('source');
            $table->string('country')->nullable()->after('desired_program');
            $table->decimal('budget', 10, 2)->nullable()->after('country');
            $table->string('last_qualification')->nullable()->after('budget');
            $table->string('cgpa')->nullable()->after('last_qualification');
            $table->unsignedTinyInteger('age')->nullable()->after('cgpa');
            $table->string('city')->nullable()->after('age');
        });
    }

    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropColumn(['desired_program', 'country', 'budget', 'last_qualification', 'cgpa', 'age', 'city']);
        });

        DB::statement("ALTER TABLE leads MODIFY COLUMN status ENUM('New', 'Contacted', 'Registered', 'Lost') DEFAULT 'New'");
    }
};
