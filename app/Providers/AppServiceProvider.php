<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
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
    }
}
