<?php

namespace App\Console\Commands;

use App\Models\Child;
use Illuminate\Console\Command;
use App\Models\RegularTaskTemplate;
use App\Models\GeneralAvailableRegularTaskTemplate;

class CreateRegularTasksForChildren extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:create-regular-tasks-for-children';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create regulas tasks for each child based on General available regular tasks';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $children = Child::all();
        $generalAvailableRegularTasks = GeneralAvailableRegularTaskTemplate::where('is_active', 1)->get();
        
        $children->each(function($child) use($generalAvailableRegularTasks){
            $activeTaskCounter = 1;
            foreach($generalAvailableRegularTasks as $task){
                $attributes = $task->getAttributes();
                unset($attributes['created_at'], $attributes['updated_at'], $attributes['id']);
                $attributes['adult_id'] = $child->adults[0]->id;
                $attributes['child_id'] = $child->id;
                
                if($activeTaskCounter < 4) {
                    $attributes['is_active'] = 1;
                    $activeTaskCounter++;
                } else {
                    $attributes['is_active'] = 0;
                }
                $attributes['is_general_available'] = true;
                
                $taskTemplate = RegularTaskTemplate::create($attributes);
                
                if(isset($task->image)){
                    $path = $task?->image?->filename;
                    $taskTemplate->image()->create(['filename' => $path]);
                }
                
            }
        });
        
        
        
    }
}
