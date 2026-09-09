<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Payment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'transaction_code',
        'booking_id',
        'user_id',
        'amount',
        'type',
        'method',
        'bank_name',
        'account_number',
        'status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($payment) {
            if (empty($payment->transaction_code)) {
                $payment->transaction_code = 'TRX'.date('Ymd').strtoupper(Str::random(6));
            }
        });
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getTypeLabelAttribute(): string
    {
        return match ($this->type) {
            'dp' => 'Uang Muka (DP)',
            'installment' => 'Cicilan',
            'final_payment' => 'Pelunasan',
            'refund' => 'Refund',
            'late_fee' => 'Denda Keterlambatan',
            'damage_fee' => 'Biaya Kerusakan',
            default => $this->type,
        };
    }

    public function getMethodLabelAttribute(): string
    {
        return match ($this->method) {
            'cash' => 'Tunai',
            'transfer' => 'Transfer Bank',
            'ewallet' => 'E-Wallet',
            'card' => 'Kartu',
            default => $this->method ?? '-',
        };
    }

    public function getPaymentDateAttribute()
    {
        return $this->created_at;
    }

    public function getPaymentMethodAttribute(): string
    {
        return $this->method_label;
    }

    public function getReferenceNumberAttribute(): ?string
    {
        return $this->transaction_code;
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'Menunggu',
            'confirmed' => 'Dikonfirmasi',
            'cancelled' => 'Dibatalkan',
            default => ucfirst($this->status ?? ''),
        };
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'confirmed' => 'bg-success',
            'pending' => 'bg-warning text-dark',
            'cancelled' => 'bg-danger',
            default => 'bg-secondary',
        };
    }
}
