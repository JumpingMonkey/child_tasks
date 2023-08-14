<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\AttachCode;
use App\Models\Reward;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Child extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $guard = 'child';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'age',
        'gender',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'remember_token',
        'adult_id'
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

    //User(child) belongs to parent. This is relation to itself
    public function adults()
    {
        return $this->belongsToMany(Adult::class);
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

    public function code()
    {
        return $this->hasOne(AttachCode::class);
    }

    
}
