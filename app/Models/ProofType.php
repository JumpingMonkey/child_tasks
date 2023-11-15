<?php

namespace App\Models;

use App\Models\GeneralAvailableRegularTaskTemplate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Spatie\Translatable\HasTranslations;

class ProofType extends Model
{
    use HasFactory, HasTranslations;

    const PROOF_TYPES = [
        ["en" => "photo", "ru" => "фото", "uk" => "фото"],
        ["en" => "screenshot", "ru" => "скриншот", "uk" => "скриншот"],
        'photo',
        'timer',
        'photo before/after',
        'photo + timer',
        'voice note',
        'screenshot',
        'picture + photo'
    ];

    protected $fillable = [
        'title'
    ];

    public $translatable = [
        'title'
    ];

    // public $casts = [
    //     'title'=> 'array',
    // ];
    
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

    public function getTitleAttribute()
    {
        return  json_decode($this->attributes['title'])->{App::getLocale()};
    }
}
