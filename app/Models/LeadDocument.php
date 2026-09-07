<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['lead_id', 'counselor_id', 'document_type', 'file_name', 'file_path', 'file_size', 'mime_type', 'notes'])]
class LeadDocument extends Model
{
    const DOCUMENT_TYPES = ['CV', 'Certificate', 'Transcript', 'IELTS', 'TOEFL', 'Passport', 'Other'];

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }

    public function counselor()
    {
        return $this->belongsTo(User::class, 'counselor_id');
    }
}
