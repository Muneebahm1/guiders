<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['application_id', 'title', 'amount', 'currency', 'due_date', 'paid_date', 'paid_amount', 'notes'])]
class Installment extends Model
{
    protected function casts(): array
    {
        return [
            'due_date' => 'date',
            'paid_date' => 'date',
        ];
    }

    public function application()
    {
        return $this->belongsTo(Application::class);
    }

    public function isPaid(): bool
    {
        return (bool) $this->paid_date;
    }

    public function isOverdue(): bool
    {
        return ! $this->isPaid() && $this->due_date->startOfDay()->lt(now()->startOfDay());
    }

    public function isDueSoon(): bool
    {
        return ! $this->isPaid() && ! $this->isOverdue() && now()->startOfDay()->diffInDays($this->due_date, false) <= 7;
    }

    public static function dueOrOverdue(int $withinDays = 7)
    {
        return static::whereNull('paid_date')->where('due_date', '<=', now()->addDays($withinDays)->endOfDay());
    }

    public function reminderLine(): string
    {
        $prefix = $this->isOverdue() ? 'Overdue' : 'Due soon';

        return "{$prefix}: {$this->application->student->name} — {$this->title} ({$this->currency} ".number_format($this->amount, 2).") due {$this->due_date->toDateString()}";
    }
}
