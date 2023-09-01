<?php

namespace App\Models;

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
        'exepted_duration',
        'status',
        'coins',
        'adult_id',
        'child_id',
        'expected_duration',
        'proof_type_id',
        'schedule_id'
    ];

    public function proofType()
    {
        return $this->belongsTo(ProofType::class);
    }

    public function schedule()
    {
        return $this->belongsTo();
    }

    public function regularTask()
    {
        return $this->hasOne(RegularTask::class);
    }
}
