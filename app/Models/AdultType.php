<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdultType extends Model
{
    use HasFactory;

    protected $fillable = [
        'title'
    ];

    public function adults()
    {
        return $this->hasMany(Adult::class);
    }
}
