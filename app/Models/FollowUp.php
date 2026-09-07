<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['counselor_id', 'lead_id', 'student_name', 'action', 'due_date'])]
class FollowUp extends Model
{
    const ACTIONS = ['Call', 'Visit'];

    protected function casts(): array
    {
        return [
            'due_date' => 'date',
        ];
    }

    public function counselor()
    {
        return $this->belongsTo(User::class, 'counselor_id');
    }

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }

    public function nextAction(): string
    {
        $i = array_search($this->action, self::ACTIONS);

        return self::ACTIONS[($i + 1) % count(self::ACTIONS)];
    }

    public function isOverdue(): bool
    {
        return $this->due_date->startOfDay()->lt(now()->startOfDay());
    }

    public function isDueSoon(): bool
    {
        return ! $this->isOverdue() && now()->startOfDay()->diffInDays($this->due_date, false) <= 2;
    }
}
