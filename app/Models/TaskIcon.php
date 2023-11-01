<?php

namespace App\Models;

use App\Models\GeneralAvailableRegularTaskTemplate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class TaskIcon extends Model
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
    public function regularTaskTemplates(): HasMany
    {
        return $this->hasMany(RegularTaskTemplate::class);
    }

    public function oneDayTasks(): HasMany
    {
        return $this->hasMany(OneDayTask::class);
    }

    public function generalAvailableRegularTasks(): HasMany
    {
        return $this->hasMany(GeneralAvailableRegularTaskTemplate::class);
    }
}
