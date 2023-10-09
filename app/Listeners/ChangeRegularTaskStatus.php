<?php

namespace App\Listeners;

use App\Events\RegularTaskWasUpdated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ChangeRegularTaskStatus
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
    public function handle(RegularTaskWasUpdated $event): void
    {
        $proofType = $event->regularTask->regularTaskTemplate->proofType->title;
    }
}
