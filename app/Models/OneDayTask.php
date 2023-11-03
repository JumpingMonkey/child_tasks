<?php

namespace App\Models;

use App\Models\Adult;
use App\Models\Child;
use App\Models\TaskIcon;
use App\Models\TaskProofImage;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Awcodes\Curator\Models\Media;

class OneDayTask extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'task_icon_id',
        'coins',
        'status',
        'expected_duration',
        'picture_proof',
        'proof_type_id',
        'start_date',
        'end_date',
        'child_id',
        'adult_id',
        'is_timer_done',
    ];

    //relations
    public function timer(): MorphOne
    {
        return $this->morphOne(Timer::class, 'timerable');
    }

    public function proofType()
    {
        return $this->belongsTo(ProofType::class);
    }

    public function child()
    {
        return $this->belongsTo(Child::class);
    }

    public function adult()
    {
        return $this->belongsTo(Adult::class);
    }

    public function imageProof()
    {
        return $this->morphMany(TaskProofImage::class, 'imageable');
    }

    public function image(): MorphOne
    {
        return $this->morphOne(TaskImage::class, 'imageable');
    }

    public function taskIcon()
    {
        return $this->belongsTo(Media::class, 'task_icon_id');
    }

    //filters

    public function scopeFilter(Builder $query, array $filters)
    {
        $query->when(
            isset($filters['status']),
            fn($query) => $query->where('status', $filters['status'])
        );
    }
}
