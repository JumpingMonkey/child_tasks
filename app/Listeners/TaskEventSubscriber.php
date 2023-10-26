<?php

namespace App\Listeners;

use App\Events\OneDayTaskStatusWasUpdated;
use App\Events\RegularTaskStatusWasUpdated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Events\Dispatcher;

class TaskEventSubscriber
{
    /**
     * Handle user login events.
     */
    public function handleUpdateRegularTask(RegularTaskStatusWasUpdated $event): void {
        $task = $event->regularTask;
    
        if ($task->status === 'checked'){
            $taskTemplate = $task->regularTaskTemplate;
            $child = $taskTemplate->child;
            $child->coins = $child->coins + $taskTemplate->coins;
            $child->save();
        }
    }
 
    /**
     * Handle user logout events.
     */
    public function handleUpdateOneDayTask(OneDayTaskStatusWasUpdated $event): void {
        $task = $event->oneDayTask;
    
        if ($task->status === 'done'){
            $child = $task->child;
            $child->coins = $child->coins + $task->coins;
            $child->save();
        }
    }
 
    /**
     * Register the listeners for the subscriber.
     */
    public function subscribe(Dispatcher $events): void
    {
        $events->listen(
            RegularTaskStatusWasUpdated::class,
            [TaskEventSubscriber::class, 'handleUpdateRegularTask']
        );
 
        $events->listen(
            OneDayTaskStatusWasUpdated::class,
            [TaskEventSubscriber::class, 'handleUpdateOneDayTask']
        );
    }
}
