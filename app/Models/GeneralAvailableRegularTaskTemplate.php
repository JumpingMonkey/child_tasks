<?php

namespace App\Models;

use App\Models\ProofType;
use App\Models\Schedule;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeneralAvailableRegularTaskTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'icon',
        'expected_duration',
        'is_general_available',
        'coins',
        'proof_type_id',
        'schedule_id',
        'status'
    ];

    public function proofType(){
        return $this->belongsTo(ProofType::class);
    }

    public function schedule(){
        return $this->belongsTo(Schedule::class);
    }
}
