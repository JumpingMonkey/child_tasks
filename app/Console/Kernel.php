<?php

namespace App\Console;

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
                    ->whereHas('schedule', function($q){
                        $q->where(Str::lower(Carbon::now()->englishDayOfWeek), 1);
                    })->get();
                foreach($regularTaskTemplates as $regularTaskTemplate){
                    
                    if(! $regularTaskTemplate->regularTask()
                        ->where('start_date', Carbon::now()->startOfDay())
                        ->exists()){
                            
                        RegularTask::factory()
                        ->for($regularTaskTemplate)
                        ->create();
                    }
                }
            }
        })->daily();
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
