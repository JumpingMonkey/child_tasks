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
}
