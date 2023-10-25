<?php

namespace App\Listeners;

use App\Events\RegularTaskStatusWasUpdated;
use App\Events\RegularTaskWasUpdated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class AddCoinsToChildAccount
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(RegularTaskStatusWasUpdated $event): void
    {
        $task = $event->regularTask;
    
        if ($task->status === 'done'){
            $taskTemplate = $task->regularTaskTemplate;
            $child = $taskTemplate->child;
            $child->coins = $child->coins + $taskTemplate->coins;
            $child->save();
        }
    }
}
