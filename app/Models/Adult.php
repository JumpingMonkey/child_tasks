<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\ChildReward;
use App\Models\OneDayTask;
use App\Models\RegularTaskTemplate;
use App\Models\Reward;
use App\Models\SocialProvider;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
        'until' => 'datetime'
    ];

    //Relationship

    //Adult(parent) has many children.
    public function children()
    {
        return $this->belongsToMany(Child::class);
    }

    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    //Api
    public function regularTaskTemplates()
    {
        return $this->hasMany(RegularTaskTemplate::class);
    }

    public function oneDayTasks()
    {
        return $this->hasMany(OneDayTask::class);
    }

    public function rewards()
    {
        return $this->hasMany(ChildReward::class);
    }

    public function adultType()
    {
        return $this->belongsTo(AdultType::class);
    }

    public function accountSettings()
    {
        return $this->hasOne(AdultAccountSettings::class);
    }

    public function socialProviders() : HasMany
    {
        return $this->hasMany(SocialProvider::class);
    }

    protected static function booted(): void
    {
        static::deleting(function (Adult $adult) {
            
            $adult->children()->delete();
        });

        static::created(function (Adult $adult) {
            $settings = new AdultAccountSettings();
            $adult->accountSettings()->save($settings);
        });
    }

    public function createdReferalCodes()
    {
        return $this->hasMany(ReferalCode::class);
    }

    public function usedReferalCodes(): BelongsToMany
    {
        return $this->belongsToMany(ReferalCode::class);
    }

    public function addTranslatedAdultType(): void
    {
        if($this->adultType()->exists()){
            $adultType = $this->adultType()->first();
            $this['adult_type'] = $adultType->translateModel();
        }
    }
}
