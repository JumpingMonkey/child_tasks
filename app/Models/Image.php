<?php

namespace App\Models;

use App\Models\Post;
use App\Models\Reward;
use App\Models\Task;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Image extends Model
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
    public function tasks(): MorphToMany
    {
        return $this->morphedByMany(Task::class, 'imageable');
    }
 
    /**
     * Get all of the videos that are assigned this tag.
     */
    public function rewards(): MorphToMany
    {
        return $this->morphedByMany(Reward::class, 'imageable');
    }
}
