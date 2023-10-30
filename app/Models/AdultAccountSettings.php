<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdultAccountSettings extends Model
{
    use HasFactory;

    protected $fillable = [
        'is_child_notification_enabled',
        'is_adult_notification_enabled',
        'language',
        'adult_id',
    ];

    protected $casts = [
        'is_child_notification_enabled' => 'boolean',
        'is_adult_notification_enabled' => 'boolean',
    ];

    public function adult()
    {
        return $this->belongsTo(Adult::class);
    } 
}
