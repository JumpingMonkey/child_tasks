<?php

namespace App\Models;

use App\Models\Adult;
use App\Models\Child;
use App\Models\RegularTaskTemplate;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class RegularTask extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'coins',
        'is_timer_done',
        'status',
    ];

    //relationship
    public function regularTaskTemplate()
    {
        return $this->belongsTo(RegularTaskTemplate::class);
    }

    public function timer(): MorphOne
    {
        return $this->morphOne(Timer::class, 'timerable');
    }

    public function imageProof()
    {
        return $this->morphMany(TaskProofImage::class, 'imageable');
    }

    //scopes

    public function scopeFilter(Builder $query, array $filters)
    {
        $query->when(
            isset($filters['status']),
            fn($query, $value) => $query->where('status', $filters['status'])
        );
    }
}
