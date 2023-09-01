<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProofType extends Model
{
    use HasFactory;

    protected $fillable = [
        'title'
    ];

    public function regularTaskTemplates()
    {
        return $this->hasMany(RegularTaskTemplate::class);
    }
}
