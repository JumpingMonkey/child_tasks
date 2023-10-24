<?php

namespace App\Models;

use App\Models\ChildRewardImage;
use App\Models\Image;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class ChildReward extends Model
{
    use HasFactory;
    
    protected $fillable =[
        'title',
        'price',
    ];

    //relationship
    public function adult()
    {
        return $this->belongsTo(Adult::class);
    }

    public function child()
    {
        return $this->belongsTo(Child::class);
    }

    public function image(): MorphOne
    {
        return $this->morphOne(ChildRewardImage::class, 'imageable');
    }

    public function imageProof(): MorphOne
    {
        return $this->morphOne(ChildRewardProofImage::class, 'imageable');
    }

    //scopes

    public function scopeFilter(Builder $query, array $filters)
    {
        $query->when(
            $filters['is_claimed'] ?? false,
            fn($query, $value) => $query->where('is_claimed', $value)
        )
        ->when(
            $filters['is_received'] ?? false,
            fn($query, $value) => $query->where('is_received', $value)
        );
    }
}

