<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Spatie\Translatable\HasTranslations;

class AdultType extends Model
{
    use HasFactory, HasTranslations;

    protected $fillable = [
        'title'
    ];

    protected $translatable = [
        'title'
    ];

    protected $appends = ['title'];

    public function getTitleAttribute()
    {
        return  json_decode($this->attributes['title'])->{App::getLocale()};
    }

    public function adults()
    {
        return $this->hasMany(Adult::class);
    }
}
