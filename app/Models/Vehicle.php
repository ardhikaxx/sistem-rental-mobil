<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vehicle extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'license_plate',
        'brand',
        'model',
        'year',
        'color',
        'transmission',
        'fuel_type',
        'passenger_count',
        'chassis_number',
        'engine_number',
        'daily_rate',
        'weekly_rate',
        'monthly_rate',
        'seasonal_rate',
        'status',
        'photo',
        'purchase_date',
        'tax_expiry_date',
        'insurance_expiry_date',
        'current_odometer',
        'notes',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'year' => 'integer',
            'passenger_count' => 'integer',
            'daily_rate' => 'decimal:2',
            'weekly_rate' => 'decimal:2',
            'monthly_rate' => 'decimal:2',
            'seasonal_rate' => 'decimal:2',
            'current_odometer' => 'integer',
            'purchase_date' => 'date',
            'tax_expiry_date' => 'date',
            'insurance_expiry_date' => 'date',
            'is_active' => 'boolean',
        ];
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function inspections()
    {
        return $this->hasMany(Inspection::class);
    }

    public function maintenanceRecords()
    {
        return $this->hasMany(MaintenanceRecord::class);
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    public function getAvailableBookings()
    {
        return $this->bookings()->whereIn('status', ['booked', 'ready_pickup', 'rented']);
    }

    public function isAvailableForDates($startDate, $endDate, $excludeBookingId = null): bool
    {
        $query = $this->bookings()
            ->whereIn('status', ['booked', 'ready_pickup', 'rented'])
            ->where(function ($q) use ($startDate, $endDate) {
                $q->where(function ($q2) use ($startDate, $endDate) {
                    $q2->where('start_date', '<=', $endDate)
                        ->where('end_date', '>=', $startDate);
                });
            });

        if ($excludeBookingId) {
            $query->where('id', '!=', $excludeBookingId);
        }

        return $query->count() === 0;
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'available' => 'Tersedia',
            'booked' => 'Dibooking',
            'rented' => 'Sedang Disewa',
            'maintenance' => 'Maintenance',
            'unavailable' => 'Tidak Tersedia',
            default => $this->status,
        };
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'available' => 'bg-success',
            'booked' => 'bg-info',
            'rented' => 'bg-primary',
            'maintenance' => 'bg-warning',
            'unavailable' => 'bg-danger',
            default => 'bg-secondary',
        };
    }
}
