<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'student_id', 'opportunity_id', 'app_stage', 'fee_status', 'fee_date',
    'visa_stage', 'visa_submission_date', 'travel_status', 'travel_date',
])]
class Application extends Model
{
    const APP_STAGES = ['Not Applied', 'Submitted', 'Waiting for Acceptance', 'Accepted', 'Rejected'];

    const VISA_STAGES = ['Not Started', 'Documentation', 'Submitted', 'Approved', 'Rejected'];

    const TRAVEL_STATUSES = ['Not Ready', 'Cleared to Travel', 'Travelled'];

    protected function casts(): array
    {
        return [
            'fee_date' => 'date',
            'visa_submission_date' => 'date',
            'travel_date' => 'date',
        ];
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function opportunity()
    {
        return $this->belongsTo(Opportunity::class);
    }

    public function installments()
    {
        return $this->hasMany(Installment::class);
    }

    public function clearance()
    {
        return $this->hasOne(Clearance::class);
    }

    public function nextAppStage(): string
    {
        $i = array_search($this->app_stage, self::APP_STAGES);

        return self::APP_STAGES[($i + 1) % count(self::APP_STAGES)];
    }

    public function nextVisaStage(): string
    {
        $i = array_search($this->visa_stage, self::VISA_STAGES);

        return self::VISA_STAGES[($i + 1) % count(self::VISA_STAGES)];
    }

    public function nextTravelStatus(): string
    {
        $i = array_search($this->travel_status, self::TRAVEL_STATUSES);

        return self::TRAVEL_STATUSES[($i + 1) % count(self::TRAVEL_STATUSES)];
    }

    public function recalculateFeeStatus(): static
    {
        $installments = $this->installments()->get();

        if ($installments->isEmpty()) {
            $this->fee_status = 'Not Set';
        } elseif ($installments->contains(fn ($i) => ! $i->paid_date && $i->due_date->isPast())) {
            $this->fee_status = 'Overdue';
        } elseif ($installments->contains(fn ($i) => ! $i->paid_date)) {
            $this->fee_status = 'Partially Paid';
        } else {
            $this->fee_status = 'Paid';
        }

        return $this;
    }

    public function canTravel(): bool
    {
        return (bool) $this->clearance?->signed_off;
    }

    public static function pendingTravelClearance()
    {
        return static::where('visa_stage', 'Approved')
            ->where('travel_status', 'Not Ready')
            ->whereDoesntHave('clearance', fn ($q) => $q->where('signed_off', true));
    }

    public function reminderLine(): string
    {
        return "Clearance pending: {$this->student->name} — visa approved, awaiting dues clearance sign-off before travel.";
    }
}
