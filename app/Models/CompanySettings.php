<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'company_name', 'address', 'bank_name', 'account_number', 'branch_code',
    'iban', 'notes', 'terms', 'updated_by_id',
])]
class CompanySettings extends Model
{
    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by_id');
    }

    public static function current(): self
    {
        return static::first();
    }
}
