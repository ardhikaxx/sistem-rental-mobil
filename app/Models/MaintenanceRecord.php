<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MaintenanceRecord extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'vehicle_id',
        'service_type',
        'service_date',
        'odometer_at_service',
        'workshop',
        'cost',
        'description',
        'status',
        'next_service_date',
        'next_service_odometer',
    ];

    protected function casts(): array
    {
        return [
            'service_date' => 'date',
            'odometer_at_service' => 'integer',
            'cost' => 'decimal:2',
            'next_service_date' => 'date',
            'next_service_odometer' => 'integer',
        ];
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function getTypeAttribute(): string
    {
        return $this->service_type ?? 'service';
    }

    public function setTypeAttribute($value): void
    {
        $this->attributes['service_type'] = $value;
    }

    public function getScheduledDateAttribute()
    {
        return $this->service_date;
    }

    public function setScheduledDateAttribute($value): void
    {
        $this->attributes['service_date'] = $value;
    }

    public function getCompletedDateAttribute()
    {
        return $this->status === 'completed' ? $this->updated_at : null;
    }

    public function getEstimatedCostAttribute(): float
    {
        return (float) ($this->cost ?? 0);
    }

    public function getActualCostAttribute(): float
    {
        return (float) ($this->cost ?? 0);
    }

    public function getOdometerReadingAttribute(): ?int
    {
        return $this->odometer_at_service;
    }

    public function getNotesAttribute(): ?string
    {
        return $this->workshop;
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'scheduled' => 'Terjadwal',
            'in_progress' => 'Dikerjakan',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
            default => ucfirst($this->status ?? ''),
        };
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'scheduled' => 'bg-info',
            'in_progress' => 'bg-warning text-dark',
            'completed' => 'bg-success',
            'cancelled' => 'bg-danger',
            default => 'bg-secondary',
        };
    }
}
