<?php

namespace App\Models;

use App\Models\Adult;
use App\Models\Child;
use App\Models\RegularTaskTemplate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class RegularTask extends Model
{
    use HasFactory;

    protected $fillable = [
        'coins',
        'picture_proof',
        'status',
    ];

    public function regularTaskTemplate()
    {
        return $this->belongsTo(RegularTaskTemplate::class);
    }

    public function timer(): MorphOne
    {
        return $this->morphOne(Timer::class, 'timerable');
    }
}
