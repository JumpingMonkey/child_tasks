<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Models\Adult;
use App\Models\Child;
use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Response;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        Gate::define('is_parent', function (User $user) {
            return $user->is_parent;
        });

        Gate::define('is_child', function (User $user) {
            return !$user->is_parent;
        });

        Gate::define('is_real_parent', function(User $user, $parent_id){
            
            return $user->id == $parent_id 
            ? Response::allow('Child was detached!')
                : Response::deny('You must be an parent of this child');    
        });

        Gate::define('is_related_adult', function(Adult $adult, Child $child){
            return $adult->children->contains($child->id);
        });
    }
}
