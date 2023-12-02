<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ReferalCode extends Model
{
    use HasFactory;

    protected $fillable = [
        "code",
    ];

    public function adultCreator()
    {
        return $this->belongsTo(Adult::class);
    }

    public function adultsWhoUsed(): BelongsToMany
    {
        return $this->belongsToMany(Adult::class);
    }
}
