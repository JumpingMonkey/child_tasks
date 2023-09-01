<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Timer extends Model
{
    use HasFactory;

    protected $fillable = [
        'start_date',
        'end_date',
        'expected_duration',
        'duration',
    ];

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }
}
