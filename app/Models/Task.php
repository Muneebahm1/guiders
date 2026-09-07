<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'title', 'notes', 'assigned_to_id', 'created_by_id', 'due_date', 'due_time',
    'priority', 'done', 'completed_at',
])]
class Task extends Model
{
    protected function casts(): array
    {
        return [
            'due_date' => 'date',
            'done' => 'boolean',
            'completed_at' => 'datetime',
        ];
    }

    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    public function isOverdue(): bool
    {
        if ($this->done || ! $this->due_date) {
            return false;
        }

        $due = $this->due_time
            ? $this->due_date->copy()->setTimeFromTimeString($this->due_time)
            : $this->due_date->copy()->endOfDay();

        return $due->isPast();
    }
}
