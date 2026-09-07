<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['lead_id', 'counselor_id', 'reminder_date', 'title', 'notes', 'status', 'snoozed_until'])]
class LeadReminder extends Model
{
    const STATUSES = ['pending', 'completed', 'snoozed'];

    protected $casts = [
        'reminder_date' => 'datetime',
        'snoozed_until' => 'datetime',
    ];

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }

    public function counselor()
    {
        return $this->belongsTo(User::class, 'counselor_id');
    }

    public function isDue(): bool
    {
        if ($this->status === 'completed') return false;
        if ($this->status === 'snoozed' && $this->snoozed_until && $this->snoozed_until > now()) return false;
        return $this->reminder_date <= now();
    }

    public function isOverdue(): bool
    {
        if ($this->status === 'completed') return false;
        if ($this->status === 'snoozed' && $this->snoozed_until && $this->snoozed_until > now()) return false;
        return $this->reminder_date < now()->startOfDay();
    }
}
