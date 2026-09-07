<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'name', 'country', 'type', 'deadline', 'requirements',
    'official_link', 'application_link', 'cost', 'currency', 'guidance',
])]
class Opportunity extends Model
{
    protected function casts(): array
    {
        return [
            'deadline' => 'date',
            'cost' => 'decimal:2',
        ];
    }

    public function students()
    {
        return $this->hasMany(Student::class);
    }

    public function sessions()
    {
        return $this->hasMany(OpportunitySession::class);
    }

    public function daysLeft(): int
    {
        return (int) round(now()->startOfDay()->diffInDays($this->deadline, false));
    }
}
