<?php

namespace App\Listeners;

use App\Events\RegularTaskTemplateStatusWasUpdated;
use App\Models\RegularTask;
use App\Models\Timer;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Str;

class CreateRegularTask
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
    public function handle(RegularTaskTemplateStatusWasUpdated $event): void
    {
        $schedule = $event->regularTaskTemplate->schedule()->firstOrFail();
        $curentWeekDay = Str::lower(Carbon::now()->englishDayOfWeek);
        
        if(! $event->regularTaskTemplate->regularTask()
            ->where('start_date', Carbon::now()->startOfDay())
            ->exists() && $schedule->$curentWeekDay){
                if($event->regularTaskTemplate->proofType->title == 'timer'){
                    RegularTask::factory()
                    ->for($event->regularTaskTemplate)
                    ->hasTimer(1, [
                        'expected_duration' => $event->regularTaskTemplate->expected_duration,
                        'duration' => 0,
                    ])
                    ->create(['picture_proof' => null]);
                } else {
                    RegularTask::factory()
                    ->for($event->regularTaskTemplate)
                    ->create();
                }
        }
        
    }
}
