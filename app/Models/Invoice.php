<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'invoice_number', 'student_id', 'application_id', 'created_by_id',
    'issued_date', 'due_date', 'subtotal', 'tax', 'total', 'currency', 'notes', 'terms',
])]
class Invoice extends Model
{
    protected function casts(): array
    {
        return [
            'issued_date' => 'date',
            'due_date' => 'date',
        ];
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function application()
    {
        return $this->belongsTo(Application::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public static function generateNumber(): string
    {
        $prefix = strtoupper(now()->format('M')).'/'.now()->format('Y');

        $count = static::where('invoice_number', 'like', '%/'.$prefix)->count();

        return sprintf('%03d/%s', $count + 1, $prefix);
    }
}
