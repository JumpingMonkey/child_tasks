<?php

namespace App\Providers;

use App\Events\OneDayTaskStatusWasUpdated;
use App\Events\RegularTaskStatusWasUpdated;
use App\Events\RegularTaskTemplateStatusWasUpdated;
use App\Listeners\AddCoinsToChildAccount;
use App\Listeners\ChangeRegularTaskStatus;
use App\Listeners\CreateRegularTask;
use App\Listeners\TaskEventSubscriber;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        RegularTaskTemplateStatusWasUpdated::class => [
            CreateRegularTask::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }

    /**
     * The subscriber classes to register.
     *
     * @var array
     */
    protected $subscribe = [
        TaskEventSubscriber::class,
    ];
}
