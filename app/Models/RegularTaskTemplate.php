<?php

namespace App\Models;

use App\Models\Adult;
use App\Models\Child;
use App\Models\ProofType;
use App\Models\RegularTask;
use App\Models\Scopes\RegularTaskRelationshipScope;
use App\Models\TaskImage;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Awcodes\Curator\Models\Media;

class RegularTaskTemplate extends Model
{
    use HasFactory;

    const REQUIRED_RELATIONSHIPS = [
        'image',
        'schedule'
    ];

    public $with = self::REQUIRED_RELATIONSHIPS;
    protected $fillable = [
        'title',
        'description',
        'task_icon_id',
        'is_general_available',
        'is_active',
        'coins',
        'adult_id',
        'child_id',
        'expected_duration',
        'proof_type_id',
        'schedule_id',
        'is_unlock_required'
    ];

    protected $casts = [
        'is_general_available' => 'boolean',
        'is_unlock_required' => 'boolean'
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

    public function taskIcon()
    {
        return $this->belongsTo(Media::class, 'task_icon_id');
    }

    public function scopeFilter(Builder $query, array $filters)
    {
        
        $query->when(
           
            isset($filters['is_unlock_required']),
            fn($query) => $query->where('is_unlock_required', $filters['is_unlock_required'])
        );
    }

    protected static function booted(): void
    {
        static::updated(function (RegularTaskTemplate $regularTaskTemplate) {
            
            if($regularTaskTemplate->is_active && $regularTaskTemplate->is_unlock_required){
                $regularTaskTemplate->is_unlock_required = false;
                $regularTaskTemplate->save();
            }
        });
    }
}
