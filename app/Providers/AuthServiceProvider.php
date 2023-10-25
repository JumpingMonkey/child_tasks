<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Models\Adult;
use App\Models\Child;
use App\Models\OneDayTask;
use App\Models\RegularTask;
use App\Models\RegularTaskTemplate;
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

        //api gates
        Gate::define('is_related_adult', function(Adult $adult, Child $child){
            return $adult->children->contains($child->id);
        });

        Gate::define('is_related_regular_task_and_child', 
            function(Adult $adult, RegularTaskTemplate $regularTaskTemplate, Child $child){
            return $adult->id == $regularTaskTemplate->adult_id && 
                $regularTaskTemplate->child_id == $child->id;
        });

        Gate::define('is_related_regular_task', 
            function(Adult $adult, RegularTaskTemplate $regularTaskTemplate){
            return $adult->id == $regularTaskTemplate->adult_id;
        });

        Gate::define('is_related_one_day_task', 
            function(Adult $adult, OneDayTask $oneDayTask, Child $child){
            return $adult->id == $oneDayTask->adult_id && 
                $oneDayTask->child_id == $child->id;
        });

        Gate::define('is_child_model', function($child){
            return $child instanceof Child;
        });

        Gate::define('is_adult_model', function($adult){
            return $adult instanceof Adult;
        });

        Gate::define('is_childs_regular_task', function(Child $child, RegularTask $regularTask){
            
            return $regularTask->regularTaskTemplate->child_id == $child->id;
        });

        Gate::define('is_childs_one_day_task', function(Child $child, OneDayTask $oneDayTask){
            
            return $oneDayTask->child_id == $child->id;
        });

        
    }
}
