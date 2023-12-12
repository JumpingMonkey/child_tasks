<?php

namespace App\Models;

use App\Models\Child;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ShortCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'adult_id',
        'child_id',
        'expires_at'
    ];

    public function adult(): BelongsTo
    {
        return $this->belongsTo(Adult::class);
    }

    public function child(): BelongsTo
    {
        return $this->belongsTo(Child::class);
    }
}
