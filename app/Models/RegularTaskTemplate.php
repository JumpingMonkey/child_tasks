<?php

namespace App\Models;

use App\Models\Adult;
use App\Models\Child;
use App\Models\ProofType;
use App\Models\TaskImage;
use App\Models\RegularTask;
use App\Traits\Translatable;
use Awcodes\Curator\Models\Media;
use Illuminate\Support\Facades\App;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Scopes\RegularTaskRelationshipScope;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RegularTaskTemplate extends Model
{
    use HasFactory, HasTranslations, Translatable;

    const REQUIRED_RELATIONSHIPS = [
        'image',
        'schedule',
        'taskIcon',
        'proofType'
    ];

    // public $with = self::REQUIRED_RELATIONSHIPS;

    protected $hidden = [
        'task_icon_id',
        "proof_type_id",
        "schedule_id",
        "adult_id",
        "child_id",
        "created_at",
        "updated_at"
    ];
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

    protected $translatable = [
        'title',
        'description',
    ];

    protected $casts = [
        'is_general_available' => 'boolean',
        'is_unlock_required' => 'boolean',
        'title' => 'array',
        'description' => 'array'
    ];

    protected $appends = ['title', 'description'];

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

    

    public function getTitleAttribute()
    {
        $translation = json_decode($this->attributes['title']);
        
        if(!empty($translation->{App::getLocale()})){
            return  $translation->{App::getLocale()};
        } else {
            return current($translation);
        }
        
    }

    public function getDescriptionAttribute()
    {
        $translation =  json_decode($this->attributes['description']);
        if(!empty($translation->{App::getLocale()})){
            return  $translation->{App::getLocale()};
        } else {
            return current($translation);
        }
    }
}
