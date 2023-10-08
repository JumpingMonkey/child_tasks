<?php

namespace App\Http\Controllers\Api\ChildrenSide;

use App\Events\RegularTaskWasUpdated;
use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Controller;
use App\Models\OneDayTask;
use App\Models\RegularTask;
use App\Models\RegularTaskTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class TaskController extends BaseController
{
    public function getAllTasks(Request $request)
    {
        $result = [];

        $activeCurentRegularTasks = RegularTaskTemplate::query()
            ->where('child_id', $request->user()->id)
            ->where('is_active', true)
            ->whereHas('regularTask', function($q){
                $q->where('start_date', Carbon::now()->startOfDay()->toDateTimeString());
            })
            ->select(['id', 'title', 'coins', 'icon', 'image'])
            ->with('regularTask', function($q){
                $q->select('status', 'regular_task_template_id');
            })
            ->get()
            ->each(function($item){
                $item->status = $item->regularTask[0]->status;
                $item->offsetUnset('regularTask');
            });
            $result['active_curent_regular_tasks'] = $activeCurentRegularTasks;

        $activeRegularTasks = RegularTaskTemplate::query()
            ->where('child_id', $request->user()->id)
            
            ->whereDoesntHave('regularTask', function($q){
                $q->where('start_date', Carbon::now()->startOfDay()->toDateTimeString());
            })
            ->select(['id', 'title', 'coins', 'icon', 'image', 'is_active'])
            ->orderBy('is_active', 'desc')
            ->get();
            
            $result['regular_tasks'] = $activeRegularTasks;

        $oneDayTasks = OneDayTask::select(['id', 'title', 'icon', 'coins', 'status', 'image'])
        ->where('child_id', $request->user()->id)
        ->get();
        $result['one_day_tasks'] = $oneDayTasks;

        return $this->sendResponseWithData($result, 200);
    }

    public function getRegularTask(Request $request, RegularTask $regularTask)
    {
        if(! Gate::allows('is_child_model', $request->user())){
            abort(403, 'You should be a child!');
        }

        if(! Gate::allows('is_childs_task', $regularTask)){
            abort(403, 'It is not your task!');
        }

        $regularTask = $regularTask->load(['regularTaskTemplate', 'timer', '']);
  
        return $this->sendResponseWithData($regularTask, 200);
    }

    public function updateRegularTask(Request $request, RegularTask $regularTask)
    {
        if(! Gate::allows('is_child_model', $request->user())){
            abort(403, 'You should be a child!');
        }

        if(! Gate::allows('is_childs_task', $regularTask)){
            abort(403, 'It is not your task!');
        }

        $validate = $request->validate([
            'is_timer_done' => 'sometimes|boolean',
            'is_before' => 'sometimes|boolean'
        ]);
        
        $regularTask->update($validate);

        if ($request->hasFile('imageProof'))
        {
            $request->validate([
                'imageProof.*' => 'mimes:jpg,png,jpeg|max:5000'
            ], [
                'imageProof.*.mimes' => 'The file should be in one of the formats: png, jpg, jpeg',
            ]);
            
            $path = $request->file('imageProof')
                ->store('regular-tasks-proof-images', 'public');

            // Storage::disk('public')->delete($regularTask->imageProof->filename);

            $is_before = array_key_exists('is_before', $validate) ? 
                $validate['is_before'] : 
                null;
                    
            $regularTask->imageProof()->create([
                'filename' => $path,
                'is_before' => $is_before,
            ]);

            $regularTask->imageProof;   
        }

        // if($regTaskTemp->is_active && $regularTaskStatusBefore != $regTaskTemp->is_active){
        //     RegularTaskWasUpdated::dispatch($regTaskTemp);
        // }

        return $this->sendResponseWithData($regularTask, 200);
    }

}
