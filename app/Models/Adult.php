<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
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
        'adult_type',
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

    //User(parent) has many children. This is relation to itself
    public function children()
    {
        return $this->belongsToMany(Child::class);
    }
    
    //User can be owner many tasks
    public function createdTasks()
    {
        return $this->hasMany(Task::class);
    }

    //User can created many rewards
    public function rewards()
    {
        return $this->hasMany(Reward::class);
    }

    //User can claim rewards
    public function claimedRewards()
    {
        return $this->hasMany(Reward::class, 'claimed_by', 'id');
    }

    public function tasksForUser()
    {
        return $this->hasMany(Task::class, 'executor_id');
    }
}
