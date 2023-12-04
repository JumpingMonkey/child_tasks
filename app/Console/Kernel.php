<?php

namespace App\Console;

use App\Models\Adult;
use App\Models\Child;
use App\Models\RegularTask;
use App\Models\RegularTaskTemplate;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Carbon\Carbon;
use Illuminate\Support\Str;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->call(function(){
            $children = Child::all();
            foreach($children as $child){
                $regularTaskTemplates = RegularTaskTemplate::where('child_id', $child->id)
                    ->where('is_active', 1)
                    ->whereHas('schedule', function($q){
                        $q->where(Str::lower(Carbon::now()->englishDayOfWeek), 1);
                    })->get();
                foreach($regularTaskTemplates as $regularTaskTemplate){
                    dump($regularTaskTemplate->id);
                    // if(! $regularTaskTemplate->regularTask()
                    //     ->where('start_date', Carbon::now()->startOfDay()->toDateTimeString())
                    //     ->exists()){

                            
                    //     RegularTask::factory(1)
                    //     ->for($regularTaskTemplate)
                    //     ->create();
                    // }
                }
            }
            
            $adults = Adult::where('is_premium', 1)->get();
            $adults->each(function($adult){
                if($adult->until < now()){
                    $adult->is_premium = 0;
                    $adult->save();
                }
            });

        })->everyMinute();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
