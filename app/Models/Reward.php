<?php

namespace App\Models;

use App\Models\Image;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

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

    public function claimedBy()
    {
        return $this->belongsTo(User::class, 'claimed_by', 'id', 'claimedRewards');
    }

    /**
     * Get all of the tags for the post.
     */
    public function images(): MorphToMany
    {
        return $this->morphToMany(Image::class, 'imageable', 'imageables', 'imageable_id');
    }

    /**
     * Set the value of attribute as current date plus numbers of days.
     *
     * @param  mixed  $value
     * @return void
     */
    public function setDataTimeAsCurentDatePlusNumbersOfDays($atributeName, $numbersOfDays = 3)
    {
        $this->attributes[$atributeName] = Carbon::now()->addDays($numbersOfDays);
    }
}
