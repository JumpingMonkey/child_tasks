<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    //Relationship

    //Task owner, creator
    public function creator()
    {
        return $this->belongsTo(User::class);
    }

    //Executor
    public function executor()
    {
        return $this->belongsTo(User::class, 'executor_id');
    }

    // Tasks belong to statuses
    public function status()
    {
        return $this->belongsTo(TaskStatus::class);
    }
}
