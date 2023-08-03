<?php

namespace App\Models;

use App\Models\Image;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'task_status_id',
        'planned_and_date',
        'is_image_required',
        'coins',
        'executor_id',
    ];

    public $statuses = [
        'new',
        'in progress',
        'review',
        'done',
        'overdue',
    ];

    public $casts = [
        'is_image_required' => 'boolean',
        'is_done' => 'boolean',
    ];

    //Relationship

    //Task owner, creator
    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    //Executor
    public function executor()
    {
        return $this->belongsTo(User::class, 'executor_id');
    }

    // Tasks belong to statuses
    public function status()
    {
        return $this->belongsTo(TaskStatus::class, 'task_status_id');
    }

    public function scopeLatest(Builder $query): void
    {
        $query->orderByDesc();
    }

    /**
     * Get all of the tags for the post.
     */
    public function images(): MorphToMany
    {
        return $this->morphToMany(Image::class, 'imageable', 'imageables', 'imageable_id');
    }

    public function scopeFilter(Builder $query, array $filters): void
    {
        $query->when($filters['status'] ?? false, 
        fn($query, $value) => $query->where('task_status_id', $filters['status']));
    }
}
