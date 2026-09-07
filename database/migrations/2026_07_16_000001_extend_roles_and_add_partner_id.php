<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'counselor', 'processing_team', 'student', 'partner') DEFAULT 'student'");

        Schema::table('students', function (Blueprint $table) {
            $table->foreignId('partner_id')->nullable()->after('counselor_id')->constrained('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropConstrainedForeignId('partner_id');
        });

        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'counselor', 'student') DEFAULT 'student'");
    }
};
