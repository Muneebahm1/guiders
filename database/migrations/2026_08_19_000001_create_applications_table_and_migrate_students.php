<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('opportunity_id')->nullable()->constrained('opportunities')->nullOnDelete();
            $table->enum('app_stage', ['Not Applied', 'Submitted', 'Waiting for Acceptance', 'Accepted', 'Rejected'])->default('Not Applied');
            $table->enum('fee_status', ['Not Set', 'Pending', 'Partially Paid', 'Paid', 'Overdue'])->default('Not Set');
            $table->date('fee_date')->nullable();
            $table->enum('visa_stage', ['Not Started', 'Documentation', 'Submitted', 'Approved', 'Rejected'])->default('Not Started');
            $table->date('visa_submission_date')->nullable();
            $table->enum('travel_status', ['Not Ready', 'Cleared to Travel', 'Travelled'])->default('Not Ready');
            $table->date('travel_date')->nullable();
            $table->timestamps();
            $table->index('student_id');
        });

        $visaStageMap = [
            'Not Started' => 'Not Started',
            'Fee Paid' => 'Not Started',
            'Visa Documentation' => 'Documentation',
            'Visa Process' => 'Submitted',
            'Visa Approved' => 'Approved',
            'Traveling' => 'Approved',
            'Visa Rejected' => 'Rejected',
        ];

        DB::table('students')->orderBy('id')->chunkById(100, function ($students) use ($visaStageMap) {
            foreach ($students as $student) {
                if (! $student->opportunity_id && $student->app_stage === 'Not Applied' && $student->visa_stage === 'Not Started') {
                    continue;
                }

                DB::table('applications')->insert([
                    'student_id' => $student->id,
                    'opportunity_id' => $student->opportunity_id,
                    'app_stage' => $student->app_stage ?? 'Not Applied',
                    'fee_status' => $student->fee_date ? 'Paid' : 'Not Set',
                    'fee_date' => $student->fee_date,
                    'visa_stage' => $visaStageMap[$student->visa_stage] ?? 'Not Started',
                    'visa_submission_date' => null,
                    'travel_status' => $student->visa_stage === 'Traveling' ? 'Cleared to Travel' : 'Not Ready',
                    'travel_date' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        });

        Schema::table('students', function (Blueprint $table) {
            $table->dropForeign(['opportunity_id']);
            $table->dropColumn(['opportunity_id', 'app_stage', 'fee_date', 'visa_stage', 'visa_date']);
        });
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->foreignId('opportunity_id')->nullable()->after('partner_id')->constrained('opportunities')->nullOnDelete();
            $table->enum('app_stage', ['Not Applied', 'Submitted', 'Waiting for Acceptance', 'Accepted', 'Rejected'])->default('Not Applied');
            $table->date('fee_date')->nullable();
            $table->enum('visa_stage', ['Not Started', 'Fee Paid', 'Visa Documentation', 'Visa Process', 'Visa Approved', 'Traveling', 'Visa Rejected'])->default('Not Started');
            $table->date('visa_date')->nullable();
        });

        $visaStageMap = [
            'Not Started' => 'Not Started',
            'Documentation' => 'Visa Documentation',
            'Submitted' => 'Visa Process',
            'Approved' => 'Visa Approved',
            'Rejected' => 'Visa Rejected',
        ];

        // Lossy: only the first application per student is restored onto the students table.
        DB::table('students')->orderBy('id')->chunkById(100, function ($students) use ($visaStageMap) {
            foreach ($students as $student) {
                $application = DB::table('applications')->where('student_id', $student->id)->orderBy('id')->first();

                if (! $application) {
                    continue;
                }

                DB::table('students')->where('id', $student->id)->update([
                    'opportunity_id' => $application->opportunity_id,
                    'app_stage' => $application->app_stage,
                    'fee_date' => $application->fee_date,
                    'visa_stage' => $application->travel_status === 'Cleared to Travel' || $application->travel_status === 'Travelled'
                        ? 'Traveling'
                        : ($visaStageMap[$application->visa_stage] ?? 'Not Started'),
                    'visa_date' => $application->visa_submission_date,
                ]);
            }
        });

        Schema::dropIfExists('applications');
    }
};
