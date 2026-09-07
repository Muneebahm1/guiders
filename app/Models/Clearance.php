<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['application_id', 'tuition_cleared', 'service_dues_cleared', 'remarks', 'signed_off', 'signed_off_by_id', 'signed_off_at'])]
class Clearance extends Model
{
    protected function casts(): array
    {
        return [
            'tuition_cleared' => 'boolean',
            'service_dues_cleared' => 'boolean',
            'signed_off' => 'boolean',
            'signed_off_at' => 'datetime',
        ];
    }

    public function application()
    {
        return $this->belongsTo(Application::class);
    }

    public function signedOffBy()
    {
        return $this->belongsTo(User::class, 'signed_off_by_id');
    }

    public function allInstallmentsPaid(): bool
    {
        return $this->application->installments()->whereNull('paid_date')->doesntExist();
    }
}
