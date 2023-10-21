<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\RegularTask;
use App\Models\Reward;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Adult extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $guard = 'adult';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_premium',
        'until',
        'adult_type_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    //Relationship

    //Adult(parent) has many children.
    public function children()
    {
        return $this->belongsToMany(Child::class)->withPivot('adult_type');
    }

    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    //Api
    public function regularTasks()
    {
        return $this->hasMany(RegularTask::class);
    }

    public function adultType()
    {
        return $this->belongsTo(AdultType::class);
    }
}
