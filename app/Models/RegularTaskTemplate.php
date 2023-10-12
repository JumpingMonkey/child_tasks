<?php

namespace App\Models;

use App\Models\Adult;
use App\Models\Child;
use App\Models\ProofType;
use App\Models\RegularTask;
use App\Models\TaskImage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class RegularTaskTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'icon',
        'is_general_available',
        'is_active',
        'coins',
        'adult_id',
        'child_id',
        'expected_duration',
        'proof_type_id',
        'schedule_id'
    ];

    protected $casts = [
        'is_general_available' => 'boolean'
    ];

    public function proofType()
    {
        return $this->belongsTo(ProofType::class);
    }

    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }

    public function regularTask()
    {
        return $this->hasMany(RegularTask::class);
    }

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
        return $this->morphOne(TaskImage::class, 'imageable');
    }
}
