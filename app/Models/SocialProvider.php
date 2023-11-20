<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SocialProvider extends Model
{
    use HasFactory;

    protected $fillable = [
        'provider',
        'provider_id',
        'adult_id',
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];
}
