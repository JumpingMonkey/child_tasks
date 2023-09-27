<?php

namespace App\Models;

use App\Models\ChildRewardImage;
use App\Models\Image;
use App\Models\User;
use Carbon\Carbon;
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

    public function adult()
    {
        return $this->belongsTo(Adult::class);
    }

    public function child()
    {
        return $this->belongsTo(Child::class);
    }

    /**
     * Get all of the tags for the post.
     */
    public function image(): MorphOne
    {
        return $this->morphOne(ChildRewardImage::class, 'imageable');
    }
}

