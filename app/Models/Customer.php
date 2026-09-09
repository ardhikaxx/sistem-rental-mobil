<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'phone',
        'address',
        'identity_number',
        'identity_type',
        'sim_number',
        'emergency_contact_name',
        'emergency_contact_phone',
        'verification_status',
        'verification_notes',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function documents()
    {
        return $this->hasMany(CustomerDocument::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function getVerificationStatusLabelAttribute(): string
    {
        return match ($this->verification_status) {
            'unverified' => 'Belum Diverifikasi',
            'verified' => 'Terverifikasi',
            'problem' => 'Bermasalah',
            default => $this->verification_status,
        };
    }

    public function getVerificationBadgeClassAttribute(): string
    {
        return match ($this->verification_status) {
            'unverified' => 'bg-secondary',
            'verified' => 'bg-success',
            'problem' => 'bg-danger',
            default => 'bg-secondary',
        };
    }
}
