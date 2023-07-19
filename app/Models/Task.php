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
        'status',
        'planned_and_date',
        'is_image_required',
        'coins',
    ];

    public $statuses = [
        'new',
        'in progress',
        'review',
        'done',
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
}
