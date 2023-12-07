<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ReferalCode extends Model
{
    use HasFactory;

    protected $fillable = [
        "code",
        'adult_id'
    ];

    public function adultCreator(): BelongsTo
    {
        return $this->belongsTo(Adult::class, 'adult_id', 'id');
    }

    public function adultsWhoUsed(): BelongsToMany
    {
        return $this->belongsToMany(Adult::class);
    }
}
