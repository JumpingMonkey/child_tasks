<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Timer extends Model
{
    use HasFactory;

    protected $fillable = [
        'expected_duration',
        'duration',
    ];

    public function timerable(): MorphTo
    {
        return $this->morphTo();
    }
}
