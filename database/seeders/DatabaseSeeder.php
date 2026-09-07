<?php

namespace Database\Seeders;

use App\Models\FollowUp;
use App\Models\Lead;
use App\Models\Opportunity;
use App\Models\Paper;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $admin = User::factory()->create([
            'name' => 'Hassan Raza',
            'email' => 'admin@theguiders.com',
            'role' => 'admin',
        ]);

        $ops = User::factory()->create([
            'name' => 'Fatima Sheikh',
            'email' => 'ops@theguiders.com',
            'role' => 'processing_team',
        ]);

        $ayesha = User::factory()->create([
            'name' => 'Ayesha Siddiqui',
            'email' => 'ayesha@theguiders.com',
            'role' => 'counselor',
        ]);

        $bilal = User::factory()->create([
            'name' => 'Bilal Chaudhry',
            'email' => 'bilal@theguiders.com',
            'role' => 'counselor',
        ]);

        $partner = User::factory()->create([
            'name' => 'Horizon Education Partners',
            'email' => 'partner@theguiders.com',
            'role' => 'partner',
        ]);

        $studentUser = User::factory()->create([
            'name' => 'Zara Ahmed',
            'email' => 'student@theguiders.com',
            'role' => 'student',
        ]);

        $munich = Opportunity::create([
            'name' => 'MSc Data Science — TU Munich',
            'country' => 'Germany',
            'type' => 'study_abroad',
            'deadline' => now()->addDays(40),
            'requirements' => "IELTS 6.5+, Bachelor's in CS/related, 2 reference letters, SOP",
            'official_link' => 'https://www.tum.de',
            'application_link' => 'https://www.tum.de/apply',
            'cost' => 1200,
            'currency' => 'USD',
            'guidance' => "Application fee \$75, visa fee \$95, translation/attestation ~\$150.\nSteps: IELTS, attested transcripts, SOP + 2 references, submit via portal, blocked account, visa appointment.",
        ]);

        $erasmus = Opportunity::create([
            'name' => 'Erasmus Mundus Scholarship',
            'country' => 'EU',
            'type' => 'study_abroad',
            'deadline' => now()->addDays(4),
            'requirements' => "Bachelor's degree, IELTS 6.5, motivation letter, CV",
            'official_link' => 'https://www.eacea.ec.europa.eu',
            'application_link' => 'https://www.eacea.ec.europa.eu/apply',
            'cost' => 400,
            'currency' => 'USD',
            'guidance' => 'Mostly document prep and courier fees — scholarship covers tuition.',
        ]);

        $toronto = Opportunity::create([
            'name' => 'MBA — University of Toronto',
            'country' => 'Canada',
            'type' => 'study_abroad',
            'deadline' => now()->addDays(86),
            'requirements' => 'GMAT 650+, 3 years work experience, IELTS 7.0, 2 essays',
            'official_link' => 'https://www.rotman.utoronto.ca',
            'application_link' => 'https://www.rotman.utoronto.ca/apply',
            'cost' => 2500,
            'currency' => 'USD',
            'guidance' => 'Application fee $200, GMAT $275, IELTS $220, study permit ~$235.',
        ]);

        $jap = Opportunity::create([
            'name' => 'Journal of Applied Physics',
            'country' => 'USA',
            'type' => 'research_publication',
            'deadline' => now()->addDays(14),
            'requirements' => 'Original research, max 8000 words, APA format, plagiarism <10%',
            'official_link' => 'https://publishing.aip.org/jap',
            'application_link' => 'https://www.editorialmanager.com/jap',
            'cost' => 350,
            'currency' => 'USD',
            'guidance' => 'Submission/processing fee; open-access fee extra if selected.',
        ]);

        $ijair = Opportunity::create([
            'name' => 'International Journal of AI Research',
            'country' => 'UK',
            'type' => 'research_publication',
            'deadline' => now()->addDays(26),
            'requirements' => 'Peer-reviewed original work, max 10 pages, IEEE format',
            'official_link' => 'https://www.ijair.org',
            'application_link' => 'https://www.ijair.org/submit',
            'cost' => 280,
            'currency' => 'USD',
            'guidance' => 'Submission fee; publication fee extra if open access.',
        ]);

        $mahnoor = Lead::create([
            'counselor_id' => $ayesha->id,
            'name' => 'Mahnoor Iqbal',
            'contact' => 'mahnoor@example.com',
            'source' => 'Social Media',
            'status' => 'New',
            'desired_program' => 'MSc Data Science',
            'country' => 'Germany',
            'budget' => 1500,
            'last_qualification' => "Bachelor's in Computer Science",
            'cgpa' => '3.6/4.0',
            'age' => 23,
            'city' => 'Lahore',
            'suggestion' => 'Suggested MSc Data Science at TU Munich given budget and CGPA',
            'remarks' => 'First follow-up after inquiry',
        ]);
        Lead::create(['counselor_id' => $ayesha->id, 'name' => 'Hamza Yousuf', 'contact' => 'hamza.y@example.com', 'source' => 'Reference', 'status' => 'Visit']);
        Lead::create(['counselor_id' => $bilal->id, 'name' => 'Areeba Khan', 'contact' => 'areeba@example.com', 'source' => 'Walk-in', 'status' => 'Registered']);

        $zara = Student::create([
            'user_id' => $studentUser->id,
            'counselor_id' => $bilal->id,
            'name' => $studentUser->name,
        ]);

        $zaraErasmus = $zara->applications()->create([
            'opportunity_id' => $erasmus->id,
            'app_stage' => 'Accepted',
            'fee_status' => 'Paid',
            'fee_date' => now()->subDays(16),
            'visa_stage' => 'Submitted',
            'visa_submission_date' => now()->subDays(8),
        ]);

        $zaraErasmus->installments()->createMany([
            ['title' => 'Deposit', 'amount' => 400, 'currency' => 'USD', 'due_date' => now()->subDays(20), 'paid_date' => now()->subDays(16), 'paid_amount' => 400],
            ['title' => 'Final Balance', 'amount' => 600, 'currency' => 'USD', 'due_date' => now()->addDays(5)],
        ]);

        // Also applying to a second program in parallel — demonstrates multi-application tracking.
        $zara->applications()->create([
            'opportunity_id' => $munich->id,
            'app_stage' => 'Waiting for Acceptance',
        ]);

        $danish = Student::create(['counselor_id' => $ayesha->id, 'name' => 'Danish Farooqi']);
        $danish->applications()->create(['opportunity_id' => $munich->id, 'app_stage' => 'Waiting for Acceptance']);

        $sana = Student::create(['counselor_id' => $ayesha->id, 'name' => 'Sana Malik']);
        $sana->applications()->create(['opportunity_id' => $toronto->id, 'app_stage' => 'Not Applied']);

        $rayyan = Student::create(['partner_id' => $partner->id, 'name' => 'Rayyan Qureshi']);
        $rayyan->applications()->create(['opportunity_id' => $toronto->id, 'app_stage' => 'Submitted']);

        Paper::create(['counselor_id' => $bilal->id, 'opportunity_id' => $jap->id, 'author_name' => 'Owais Anwar', 'submitted_date' => now()->subDays(57), 'status' => 'Under Review']);
        Paper::create(['counselor_id' => $ayesha->id, 'opportunity_id' => $ijair->id, 'author_name' => 'Nimra Javed', 'submitted_date' => now()->subDays(35), 'status' => 'Submitted']);
        Paper::create(['counselor_id' => $bilal->id, 'opportunity_id' => $ijair->id, 'author_name' => 'Zara Ahmed', 'submitted_date' => now()->subDays(21), 'status' => 'Under Review']);

        FollowUp::create([
            'counselor_id' => $ayesha->id,
            'student_name' => 'Danish Farooqi',
            'action' => 'Call',
            'due_date' => now()->subDays(1),
        ]);
        FollowUp::create([
            'counselor_id' => $ayesha->id,
            'student_name' => 'Sana Malik',
            'action' => 'Visit',
            'due_date' => now()->addDays(3),
        ]);
        FollowUp::create([
            'counselor_id' => $ayesha->id,
            'lead_id' => $mahnoor->id,
            'student_name' => $mahnoor->name,
            'action' => 'Call',
            'due_date' => now()->addDays(6),
        ]);
    }
}
