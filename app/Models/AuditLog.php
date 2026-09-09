<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    protected $fillable = [
        'user_id',
        'role',
        'action',
        'module',
        'reference_type',
        'reference_id',
        'description',
        'old_values',
        'new_values',
        'ip_address',
        'user_agent',
    ];

    protected function casts(): array
    {
        return [
            'old_values' => 'array',
            'new_values' => 'array',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function log($action, $module = null, $description = null, $reference = null, $oldValues = null, $newValues = null): static
    {
        $user = auth()->user();

        return static::create([
            'user_id' => $user?->id,
            'role' => $user?->role,
            'action' => $action,
            'module' => $module,
            'reference_type' => $reference ? get_class($reference) : null,
            'reference_id' => $reference?->id,
            'description' => $description,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}
