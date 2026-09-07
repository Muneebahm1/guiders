<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['lead_id', 'counselor_id', 'call_date', 'duration_seconds', 'outcome', 'notes', 'recording_url'])]
class CallLog extends Model
{
    const OUTCOMES = ['completed', 'no_answer', 'voicemail', 'busy', 'failed'];

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }

    public function counselor()
    {
        return $this->belongsTo(User::class, 'counselor_id');
    }

    public function getDurationFormattedAttribute()
    {
        if (!$this->duration_seconds) return '—';
        
        $minutes = floor($this->duration_seconds / 60);
        $seconds = $this->duration_seconds % 60;
        
        if ($minutes > 0) {
            return $minutes . 'm ' . $seconds . 's';
        }
        return $seconds . 's';
    }
}
