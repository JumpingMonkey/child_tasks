<?php

namespace App\Models;

use App\Models\Image;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Reward extends Model
{
    use HasFactory;
    
    protected $fillable =[
        'title',
        'price',
        'status',
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
    public function images(): MorphToMany
    {
        return $this->morphToMany(Image::class, 'imageable', 'imageables', 'imageable_id');
    }
}

