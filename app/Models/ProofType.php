<?php

namespace App\Models;

use App\Models\GeneralAvailableRegularTaskTemplate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProofType extends Model
{
    use HasFactory;

    const PROOF_TYPES = [
        'image',
        'timer',
        'image-before-after',
        'image+timer',
    ];

    protected $fillable = [
        'title'
    ];
//Todo rewrite relation to morph
    public function regularTaskTemplates()
    {
        return $this->hasMany(RegularTaskTemplate::class);
    }

    public function generalAvailableRegularTaskTemplates()
    {
        return $this->hasMany(GeneralAvailableRegularTaskTemplate::class);
    }

    public function OneDayTasks()
    {
        return $this->hasMany(OneDayTask::class);
    }
}
