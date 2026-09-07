<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'role'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public static function roleLabels(): array
    {
        return [
            'admin' => 'Admin',
            'counselor' => 'Counselor',
            'processing_team' => 'Processing Team',
            'student' => 'Student',
            'partner' => 'Partner Company',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isCounselor(): bool
    {
        return $this->role === 'counselor';
    }

    public function isStudent(): bool
    {
        return $this->role === 'student';
    }

    public function isProcessingTeam(): bool
    {
        return $this->role === 'processing_team';
    }

    public function isPartner(): bool
    {
        return $this->role === 'partner';
    }

    /**
     * Roles with full, cross-counselor visibility of opportunities/students/papers.
     */
    public function hasBackOfficeAccess(): bool
    {
        return $this->isAdmin() || $this->isProcessingTeam();
    }

    public function studentProfile()
    {
        return $this->hasOne(Student::class);
    }

    public function leads()
    {
        return $this->hasMany(Lead::class, 'counselor_id');
    }

    public function students()
    {
        return $this->hasMany(Student::class, 'counselor_id');
    }

    public function partnerStudents()
    {
        return $this->hasMany(Student::class, 'partner_id');
    }

    public function papers()
    {
        return $this->hasMany(Paper::class, 'counselor_id');
    }

    public function followUps()
    {
        return $this->hasMany(FollowUp::class, 'counselor_id');
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'created_by_id');
    }
}
