<?php

namespace App\Models;

use App\Models\RegularTaskTemplate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'monday',
        'tuesday',
        'wednesday',
        'thursday',
        'friday',
        'saturday',
        'sunday',
    ];

    protected $casts = [
        'monday' => 'boolean',
        'tuesday' => 'boolean',
        'wednesday' => 'boolean',
        'thursday' => 'boolean',
        'friday' => 'boolean',
        'saturday' => 'boolean',
        'sunday' => 'boolean',
    ];

    public function regularTaskTemplate()
    {
        return $this->hasMany(RegularTaskTemplate::class);
    }
}
