<?php

namespace App\Models;

use App\Models\Adult;
use App\Models\Child;
use App\Models\ProofType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegularTaskTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'icon',
        'is_general_available',
        'status',
        'coins',
        'image',
        'adult_id',
        'child_id',
        'expected_duration',
        'proof_type_id',
        'schedule_id'
    ];

    protected $appends = ['src'];

    public function getSrcAttribute()
    {
        return asset("storage/{$this->image}");
    }

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

    
}
