<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'counselor_id', 'name', 'contact', 'email', 'source', 'status',
    'desired_program', 'country', 'budget', 'last_qualification', 'cgpa', 'age', 'city',
    'suggestion', 'remarks',
    'highly_interested', 'call_log', 'messages', 'visits', 'funnel_stage',
    'contract_signed', 'contract_date', 'payment_done', 'payment_date',
    'currency', 'english_test', 'english_score',
])]
class Lead extends Model
{
    const STATUSES = ['New', 'Call', 'Visit', 'Registered'];

    const SOURCES = ['Walk-in', 'Social Media', 'Reference', 'Website', 'Agent'];

    public function counselor()
    {
        return $this->belongsTo(User::class, 'counselor_id');
    }

    public function followUps()
    {
        return $this->hasMany(FollowUp::class);
    }

    public function documents()
    {
        return $this->hasMany(LeadDocument::class);
    }

    public function callLogs()
    {
        return $this->hasMany(\App\Models\CallLog::class);
    }

    public function communications()
    {
        return $this->hasMany(\App\Models\Communication::class);
    }

    public function reminders()
    {
        return $this->hasMany(\App\Models\LeadReminder::class);
    }

    public function getMatchingOpportunities()
    {
        $query = \App\Models\Opportunity::query();

        $hasCountry = !empty($this->country);
        $hasProgram = !empty($this->desired_program);
        $hasBudget = !empty($this->budget);
        $hasCgpa = !empty($this->cgpa);
        $hasQualification = !empty($this->last_qualification);
        $hasEnglishTest = !empty($this->english_test);

        // If no criteria set, return all opportunities
        if (!$hasCountry && !$hasProgram && !$hasBudget && !$hasCgpa && !$hasQualification && !$hasEnglishTest) {
            return $query->get()->map(function ($opp) {
                $opp->match_score = 50; // Default score for no criteria
                $opp->match_reasons = ['No criteria specified'];
                return $opp;
            });
        }

        // Use OR logic - match any of the criteria
        $query->where(function ($q) use ($hasCountry, $hasProgram, $hasBudget, $hasCgpa, $hasQualification, $hasEnglishTest) {
            if ($hasCountry) {
                $q->orWhereRaw('LOWER(country) LIKE ?', ['%' . strtolower($this->country) . '%']);
            }
            if ($hasProgram) {
                $q->orWhereRaw('LOWER(type) LIKE ?', ['%' . strtolower($this->desired_program) . '%']);
            }
            if ($hasBudget) {
                $q->orWhere(function ($subQ) {
                    $subQ->whereNull('cost')
                          ->orWhere('cost', '<=', $this->budget);
                });
            }
            // CGPA and qualification matching would require opportunity to have these fields
            // For now, we'll include all opportunities and score them
        });

        $opportunities = $query->get();

        // Calculate match scores for each opportunity
        return $opportunities->map(function ($opp) use ($hasCountry, $hasProgram, $hasBudget, $hasQualification, $hasEnglishTest) {
            $score = 0;
            $reasons = [];

            // Country match (30 points)
            if ($hasCountry && stripos($opp->country, $this->country) !== false) {
                $score += 30;
                $reasons[] = 'Country match';
            }

            // Program match (25 points)
            if ($hasProgram && stripos($opp->type, $this->desired_program) !== false) {
                $score += 25;
                $reasons[] = 'Program match';
            }

            // Budget match (20 points)
            if ($hasBudget && ($opp->cost === null || $opp->cost <= $this->budget)) {
                $score += 20;
                $reasons[] = 'Within budget';
            }

            // Qualification match (15 points) - check if requirements mention qualification
            if ($hasQualification && $opp->requirements) {
                if (stripos($opp->requirements, $this->last_qualification) !== false) {
                    $score += 15;
                    $reasons[] = 'Qualification match';
                }
            }

            // English test match (10 points)
            if ($hasEnglishTest && $opp->requirements) {
                if (stripos($opp->requirements, $this->english_test) !== false) {
                    $score += 10;
                    $reasons[] = 'English test match';
                }
            }

            $opp->match_score = $score;
            $opp->match_reasons = $reasons ?: ['Partial match'];

            return $opp;
        })->sortByDesc('match_score')->values();
    }

    public function nextStatus(): string
    {
        $i = array_search($this->status, self::STATUSES);

        return self::STATUSES[($i + 1) % count(self::STATUSES)];
    }

    public function hasProfile(): bool
    {
        return $this->desired_program || $this->country || $this->budget || $this->last_qualification || $this->cgpa || $this->age || $this->city || $this->suggestion || $this->remarks;
    }

    public function getEngagementScore(): int
    {
        $score = 0;

        // Calls made (10 points each, max 50)
        $callCount = $this->callLogs()->count();
        $score += min($callCount * 10, 50);

        // Messages sent (5 points each, max 25)
        $messageCount = $this->messages ?? 0;
        $score += min($messageCount * 5, 25);

        // Visits (15 points each, max 30)
        $visitCount = $this->visits ?? 0;
        $score += min($visitCount * 15, 30);

        // Documents uploaded (10 points each, max 20)
        $docCount = $this->documents()->count();
        $score += min($docCount * 10, 20);

        // Profile completeness (15 points)
        if ($this->hasProfile()) {
            $score += 15;
        }

        // Highly interested (10 points)
        if ($this->highly_interested) {
            $score += 10;
        }

        // Contract signed (20 points)
        if ($this->contract_signed) {
            $score += 20;
        }

        // Payment done (20 points)
        if ($this->payment_done) {
            $score += 20;
        }

        return min($score, 100); // Cap at 100
    }

    public function getEngagementLevel(): string
    {
        $score = $this->getEngagementScore();
        if ($score >= 70) return 'High';
        if ($score >= 40) return 'Medium';
        return 'Low';
    }

    public function getCallLogArray(): array
    {
        return $this->call_log ? json_decode($this->call_log, true) : [];
    }

    public function getCallSequenceHtml(): string
    {
        $log = $this->getCallLogArray();
        $today = now()->format('Y-m-d');
        $gapDays = 3;
        $html = '<div style="display:flex;gap:6px;flex-wrap:wrap;">';

        for ($i = 0; $i < 3; $i++) {
            $label = 'Call ' . ($i + 1);
            if (isset($log[$i])) {
                $html .= '<span class="chip teal static">' . $label . ' ✓ ' . $log[$i] . '</span>';
            } elseif ($i === 0 || isset($log[$i - 1])) {
                $sinceDate = $i === 0 ? null : $log[$i - 1];
                if ($sinceDate) {
                    $daysSince = (strtotime($today) - strtotime($sinceDate)) / 86400;
                    $overdue = $daysSince >= $gapDays;
                    if ($overdue) {
                        $html .= '<span class="chip amber static">⏰ ' . $label . ' Due</span>';
                    } else {
                        $remaining = $gapDays - $daysSince;
                        $html .= '<span class="chip gray static">' . $label . ' — in ' . round($remaining) . 'd</span>';
                    }
                } else {
                    $html .= '<span class="chip amber static">⏰ ' . $label . ' Due</span>';
                }
            } else {
                $html .= '<span class="chip gray static">' . $label . ' — not yet</span>';
            }
        }

        $html .= '</div>';
        return $html;
    }
}
