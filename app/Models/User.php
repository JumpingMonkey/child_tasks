<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'user_name',
        'is_parent',
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
        return $this->hasMany(User::class, 'user_id');
    }
    //User(child) belongs to parent. This is relation to itself
    public function parent()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    //User can be owner many tasks
    public function createdTasks()
    {
        $this->hasMany(Task::class);
    }

    public function tasksForUser()
    {
        return $this->hasMany(Task::class, 'executor_id');
    }
}
