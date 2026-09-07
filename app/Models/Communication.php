<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['lead_id', 'counselor_id', 'type', 'subject', 'direction', 'content', 'status', 'sent_at', 'delivered_at', 'notes'])]
class Communication extends Model
{
    const TYPES = ['call', 'whatsapp', 'sms', 'email'];
    const DIRECTIONS = ['inbound', 'outbound'];
    const STATUSES = ['sent', 'delivered', 'read', 'failed'];

    protected $casts = [
        'sent_at' => 'datetime',
        'delivered_at' => 'datetime',
    ];

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }

    public function counselor()
    {
        return $this->belongsTo(User::class, 'counselor_id');
    }
}
