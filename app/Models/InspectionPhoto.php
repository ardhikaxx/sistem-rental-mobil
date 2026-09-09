<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InspectionPhoto extends Model
{
    protected $fillable = [
        'inspection_id',
        'category',
        'file_path',
    ];

    public function inspection()
    {
        return $this->belongsTo(Inspection::class);
    }
}
