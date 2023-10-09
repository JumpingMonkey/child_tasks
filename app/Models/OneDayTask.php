<?php

namespace App\Models;

use App\Models\Adult;
use App\Models\Child;
use App\Models\TaskProofImage;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class OneDayTask extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'icon',
        'image',
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

    protected $appends = ['src'];

    public function getSrcAttribute()
    {
        return asset("storage/{$this->image}");
    }

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
}
