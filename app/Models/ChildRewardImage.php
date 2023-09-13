<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ChildRewardImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'filename',
    ];
    protected $appends = ['src'];

    public function getSrcAttribute()
    {
        return asset("storage/{$this->filename}");
    }


    /**
     * Get all of the posts that are assigned this tag.
     */
    public function imageable(): MorphTo
    {
        return $this->morphTo();
    }

}
