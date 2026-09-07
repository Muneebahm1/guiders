<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("UPDATE students SET visa_stage = 'Visa Documentation' WHERE visa_stage = 'File Preparation'");
        DB::statement("UPDATE students SET visa_stage = 'Visa Process' WHERE visa_stage = 'Visa Submitted'");
        DB::statement("ALTER TABLE students MODIFY COLUMN visa_stage ENUM('Not Started', 'Fee Paid', 'Visa Documentation', 'Visa Process', 'Visa Approved', 'Traveling', 'Visa Rejected') DEFAULT 'Not Started'");
    }

    public function down(): void
    {
        DB::statement("UPDATE students SET visa_stage = 'Visa Submitted' WHERE visa_stage = 'Visa Process'");
        DB::statement("UPDATE students SET visa_stage = 'File Preparation' WHERE visa_stage = 'Visa Documentation'");
        DB::statement("UPDATE students SET visa_stage = 'Visa Approved' WHERE visa_stage = 'Traveling'");
        DB::statement("ALTER TABLE students MODIFY COLUMN visa_stage ENUM('Not Started', 'Fee Paid', 'File Preparation', 'Visa Submitted', 'Visa Approved', 'Visa Rejected') DEFAULT 'Not Started'");
    }
};
