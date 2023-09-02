<?php

namespace App\Models;

use App\Models\Adult;
use App\Models\Child;
use App\Models\RegularTaskTemplate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    public function timer()
    {
        return $this->hasOne(Timer::class);
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
