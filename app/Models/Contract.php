<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'student_id', 'created_by_id', 'guardian_name', 'cnic', 'phone', 'client_email', 'country',
    'university', 'university_tbd', 'course', 'service_type', 'service_detail',
    'intake', 'total_fee', 'currency', 'clauses', 'custom_terms', 'requirements',
])]
class Contract extends Model
{
    public const STANDARD_CLAUSES = [
        ['id' => 'scope', 'title' => 'Scope of Services', 'text' => "The Guiders will provide counselling, university/program selection, application, and visa processing support as described in this agreement, based on the information provided by the student."],
        ['id' => 'payment', 'title' => 'Payment Terms', 'text' => "The total contract fee is payable according to the installment schedule below. Delays in payment may delay processing of the student's application."],
        ['id' => 'refund', 'title' => 'Refund & Cancellation Policy', 'text' => "Refunds, where applicable, will be processed as per The Guiders' standard refund policy, minus any non-recoverable costs already incurred on the student's behalf."],
        ['id' => 'confidentiality', 'title' => 'Confidentiality', 'text' => "All personal and academic documents shared by the student will be used solely for the purpose of this engagement and kept confidential."],
        ['id' => 'responsibility', 'title' => 'Client Responsibilities', 'text' => "The student agrees to provide accurate documents and respond to requests in a timely manner, as delays may affect application outcomes."],
        ['id' => 'liability', 'title' => 'Limitation of Liability', 'text' => "The Guiders facilitates the application and visa process but is not responsible for final admission or visa decisions, which rest solely with the relevant institution or authority."],
        ['id' => 'fakedocs', 'title' => 'Authenticity of Documents', 'text' => "The student is solely responsible for the authenticity of all documents provided. If any document submitted by the student is found to be fake, forged, or fraudulent, The Guiders bears no liability for the consequences, and no amount paid under this agreement will be refunded or returned."],
        ['id' => 'law', 'title' => 'Governing Law', 'text' => "This agreement is governed by the laws of Pakistan."],
    ];

    protected function casts(): array
    {
        return [
            'university_tbd' => 'boolean',
            'total_fee' => 'decimal:2',
            'clauses' => 'array',
            'requirements' => 'array',
        ];
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    public function installments()
    {
        return $this->hasMany(ContractInstallment::class);
    }

    public function selectedClauses(): array
    {
        $ids = $this->clauses ?? [];

        return array_values(array_filter(self::STANDARD_CLAUSES, fn ($clause) => in_array($clause['id'], $ids)));
    }
}
