<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\AttachCode;
use App\Models\ChildReward;
use App\Models\RegularTask;
use App\Models\RegularTaskTemplate;
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
        'coins'
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

    //child belongs to adult.
    public function adults()
    {
        return $this->belongsToMany(Adult::class);
    }
    
    public function regularTaskTemplates()
    {
        return $this->hasMany(RegularTaskTemplate::class);
    }

    public function rewards()
    {
        return $this->HasMany(ChildReward::class, 'child_id')->with('image');
    }

    public function createAccessToken(): array
    {
        $success = [];
        $success['token'] =  $this->createToken('MyApp')->plainTextToken;
        $success['name'] =  $this->name;
        $success['id'] = $this->id;
        
        return $success;
    }
}
