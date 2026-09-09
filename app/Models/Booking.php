<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Booking extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'booking_code',
        'customer_id',
        'vehicle_id',
        'user_id',
        'start_date',
        'end_date',
        'actual_return_date',
        'rental_days',
        'daily_rate_snapshot',
        'rental_subtotal',
        'additional_fees',
        'discount',
        'discount_reason',
        'discount_note',
        'total_amount',
        'dp_amount',
        'paid_amount',
        'remaining_amount',
        'late_fee',
        'damage_fee',
        'status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'datetime',
            'end_date' => 'datetime',
            'actual_return_date' => 'datetime',
            'rental_days' => 'integer',
            'daily_rate_snapshot' => 'decimal:2',
            'rental_subtotal' => 'decimal:2',
            'additional_fees' => 'decimal:2',
            'discount' => 'decimal:2',
            'total_amount' => 'decimal:2',
            'dp_amount' => 'decimal:2',
            'paid_amount' => 'decimal:2',
            'remaining_amount' => 'decimal:2',
            'late_fee' => 'decimal:2',
            'damage_fee' => 'decimal:2',
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($booking) {
            if (empty($booking->booking_code)) {
                $booking->booking_code = 'BK'.date('Ymd').strtoupper(Str::random(6));
            }
        });
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function inspections()
    {
        return $this->hasMany(Inspection::class);
    }

    public function approvals()
    {
        return $this->hasMany(Approval::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending_payment' => 'Menunggu Pembayaran',
            'booked' => 'Terbooking',
            'ready_pickup' => 'Siap Pickup',
            'rented' => 'Sedang Disewa',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
            default => $this->status,
        };
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'pending_payment' => 'bg-secondary',
            'booked' => 'bg-info',
            'ready_pickup' => 'bg-primary',
            'rented' => 'bg-warning',
            'completed' => 'bg-success',
            'cancelled' => 'bg-danger',
            default => 'bg-secondary',
        };
    }

    public function getTotalCostAttribute(): float
    {
        return (float) ($this->total_amount ?? 0);
    }

    public function getTotalPaidAttribute(): float
    {
        return $this->payments()
            ->where('status', 'confirmed')
            ->where('type', '!=', 'refund')
            ->sum('amount');
    }

    public function calculateTotals(): void
    {
        $this->rental_subtotal = $this->daily_rate_snapshot * $this->rental_days;
        $this->total_amount = $this->rental_subtotal + $this->additional_fees - $this->discount + $this->late_fee + $this->damage_fee;
        $this->remaining_amount = max(0, $this->total_amount - $this->dp_amount - $this->paid_amount);
        $this->save();
    }

    public function recalculatePaid(): void
    {
        $this->paid_amount = $this->payments()
            ->where('status', 'confirmed')
            ->where('type', '!=', 'refund')
            ->sum('amount');
        $this->remaining_amount = max(0, $this->total_amount - $this->dp_amount - $this->paid_amount);
        $this->save();
    }
}
