<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reward extends Model
{
    use HasFactory;
    
    protected $fillable =[
        'title',
        'description',
        'price',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
