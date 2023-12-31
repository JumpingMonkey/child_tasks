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
use Illuminate\Support\Facades\Storage;

class ChildReward extends Model
{
    use HasFactory;
    
    protected $fillable =[
        'title',
        'price',
        'is_claimed',
        'is_received'
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
            isset($filters['is_claimed']),
            fn($query) => $query->where('is_claimed', $filters['is_claimed'])
        )
        ->when(
            isset($filters['is_received']),
            fn($query) => $query->where('is_received', $filters['is_received'])
        );
    }

    protected static function booted(): void
    {
        static::deleted(function (ChildReward $childReward) {
            
            if($childReward->image){
                $image = $childReward->image;
                Storage::disk('public')->delete($image->filename);
                $image->delete();
            }
            if ($childReward->imageProof){
                $imageProof = $childReward->imageProof;
                Storage::disk('public')->delete($imageProof->filename);
                $imageProof->delete();
            }
        });
    }
}

