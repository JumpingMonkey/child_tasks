<?php

namespace App\Models;

use App\Models\ProofType;
use App\Models\Schedule;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class GeneralAvailableRegularTaskTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'task_icon_id',
        'expected_duration',
        'coins',
        'proof_type_id',
        'schedule_id',
        'is_active'
    ];

    public function proofType(){
        return $this->belongsTo(ProofType::class);
    }

    public function schedule(){
        return $this->belongsTo(Schedule::class);
    }

    public function image(): MorphOne
    {
        return $this->morphOne(TaskImage::class, 'imageable');
    }

    public function icon()
    {
        return $this->belongsTo(TaskIcon::class, 'task_icon_id');
    }
}
