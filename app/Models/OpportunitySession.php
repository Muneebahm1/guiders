<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['opportunity_id', 'session_name', 'university', 'country', 'deadline', 'is_active', 'notes'])]
class OpportunitySession extends Model
{
    protected function casts(): array
    {
        return [
            'deadline' => 'date',
            'is_active' => 'boolean',
        ];
    }

    public function opportunity()
    {
        return $this->belongsTo(Opportunity::class);
    }
}
