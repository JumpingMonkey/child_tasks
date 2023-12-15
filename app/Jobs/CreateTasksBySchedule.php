<?php

namespace App\Jobs;

use Carbon\Carbon;
use App\Models\Child;
use App\Models\RegularTask;
use Illuminate\Support\Str;
use Illuminate\Bus\Queueable;
use App\Models\RegularTaskTemplate;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class CreateTasksBySchedule implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 5;

    /**
     * Indicate if the job should be marked as failed on timeout.
     *
     * @var bool
     */
    public $failOnTimeout = true;

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $children = Child::all();
            foreach($children as $child){
                $regularTaskTemplates = RegularTaskTemplate::where('child_id', $child->id)
                    ->where('is_active', 1)
                    ->whereHas('schedule', function($q){
                        $q->where(Str::lower(Carbon::now()->englishDayOfWeek), 1);
                    })->get();
                foreach($regularTaskTemplates as $regularTaskTemplate){
                    
                    if(! $regularTaskTemplate->regularTask()
                        ->where('start_date', Carbon::now()->startOfDay()->toDateTimeString())
                        ->exists()){
                               
                        $regulatTask = RegularTask::create([
                            'status' => 'should do',
                            'start_date' => Carbon::now()->startOfDay()->toDateTimeString(),
                            'end_date' => Carbon::now()->endOfDay()->toDateTimeString(),
                            'regular_task_template_id' => $regularTaskTemplate->id,
                        ]);
                    }
                }
            }
    }
}
