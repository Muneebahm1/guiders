<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['counselor_id', 'opportunity_id', 'author_name', 'submitted_date', 'status'])]
class Paper extends Model
{
    const STAGES = ['Submitted', 'Under Review', 'Revisions Requested', 'Accepted', 'Rejected'];

    protected function casts(): array
    {
        return [
            'submitted_date' => 'date',
        ];
    }

    public function counselor()
    {
        return $this->belongsTo(User::class, 'counselor_id');
    }

    public function opportunity()
    {
        return $this->belongsTo(Opportunity::class);
    }

    public function nextStatus(): string
    {
        $i = array_search($this->status, self::STAGES);

        return self::STAGES[($i + 1) % count(self::STAGES)];
    }

    public function daysInReview(): int
    {
        return (int) round($this->submitted_date->diffInDays(now(), true));
    }
}
