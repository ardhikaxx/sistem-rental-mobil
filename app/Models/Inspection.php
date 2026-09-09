<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inspection extends Model
{
    protected $fillable = [
        'booking_id',
        'vehicle_id',
        'user_id',
        'type',
        'inspection_date',
        'odometer',
        'fuel_level',
        'exterior_condition',
        'interior_condition',
        'equipment_condition',
        'previous_damage_notes',
        'new_damage_notes',
        'officer_notes',
        'late_fee',
        'damage_fee',
    ];

    protected function casts(): array
    {
        return [
            'inspection_date' => 'datetime',
            'odometer' => 'integer',
            'late_fee' => 'decimal:2',
            'damage_fee' => 'decimal:2',
        ];
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function photos()
    {
        return $this->hasMany(InspectionPhoto::class);
    }

    public function getTypeLabelAttribute(): string
    {
        return match ($this->type) {
            'check_in' => 'Check-in / Serah Terima',
            'check_out' => 'Check-out / Pengembalian',
            default => $this->type,
        };
    }

    public function getFuelLevelLabelAttribute(): string
    {
        return match ($this->fuel_level) {
            'empty' => 'Kosong',
            'quarter' => '1/4',
            'half' => '1/2',
            'three_quarter' => '3/4',
            'full' => 'Penuh',
            default => $this->fuel_level,
        };
    }
}
