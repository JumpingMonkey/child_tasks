<?php

namespace App\Models;

use App\Models\GeneralAvailableRegularTaskTemplate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class TaskIcon extends Model
{
    use HasFactory;

    protected $fillable = [
        'filename',
    ];

    protected $appends = ['src'];

    public function getSrcAttribute()
    {
        return asset("storage/{$this->filename}");
    }
}
