<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Approval extends Model
{
    protected $fillable = [
        'user_id',
        'approver_id',
        'type',
        'reference_type',
        'reference_id',
        'amount',
        'reason',
        'status',
        'approval_notes',
        'approved_at',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'approved_at' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approver_id');
    }

    public function getTypeLabelAttribute(): string
    {
        return match ($this->type) {
            'discount' => 'Diskon Khusus',
            'cancellation' => 'Pembatalan Booking',
            'refund' => 'Refund / Pengembalian Dana',
            'damage_fee' => 'Biaya Kerusakan',
            'other' => 'Lainnya',
            default => $this->type,
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'Menunggu Persetujuan',
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak',
            default => $this->status,
        };
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'bg-warning',
            'approved' => 'bg-success',
            'rejected' => 'bg-danger',
            default => 'bg-secondary',
        };
    }
}
