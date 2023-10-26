<?php

namespace App\Http\Controllers\Api\ChildrenSide;

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
            ->select(['id', 'title', 'coins', 'icon'])
            ->with('regularTask', function($q){
                $q->select('status', 'regular_task_template_id', 'id');
            })
            ->with('image')
            ->get()
            ->each(function($item){
                $item->status = $item->regularTask[0]->status;
                $item->regularTaskId = $item->regularTask[0]->id;
                $item->offsetUnset('regularTask');
            });
            $result['active_curent_regular_task'] = $activeCurentRegularTasks;

        $activeRegularTasks = RegularTaskTemplate::query()
            ->where('child_id', $request->user()->id)
            
            ->whereDoesntHave('regularTask', function($q){
                $q->where('start_date', Carbon::now()->startOfDay()->toDateTimeString());
            })
            ->select(['id', 'title', 'coins', 'icon', 'is_active'])
            ->with('image')
            ->orderBy('is_active', 'desc')
            ->get();
            
            $result['regular_tasks'] = $activeRegularTasks;

        $oneDayTasks = OneDayTask::select(['id', 'title', 'icon', 'coins', 'status'])
        ->where('child_id', $request->user()->id)
        ->where('start_date', Carbon::now()->startOfDay()->toDateTimeString())
        ->with('image')
        ->get();
        $result['one_day_tasks'] = $oneDayTasks;

        return $this->sendResponseWithData($result, 200);
    }

    public function getRegularTask(Request $request, RegularTask $regularTask)
    {
        if(! Gate::allows('is_child_model', $request->user())){
            abort(403, 'You should be a child!');
        }

        if(! Gate::allows('is_childs_regular_task', $regularTask)){
            abort(403, 'It is not your task!');
        }

        $regularTask = $regularTask->load(['regularTaskTemplate.image']);
  
        return $this->sendResponseWithData($regularTask, 200);
    }

    public function getOneDayTask(Request $request, OneDayTask $oneDayTask)
    {
        if(! Gate::allows('is_child_model', $request->user())){
            abort(403, 'You should be a child!');
        }

        if(! Gate::allows('is_childs_one_day_task', $oneDayTask)){
            abort(403, 'It is not your task!');
        }

        $oneDayTask = $oneDayTask->load(['image', 'proofType']);
  
        return $this->sendResponseWithData($oneDayTask, 200);
    }

    public function updateRegularTask(Request $request, RegularTask $regularTask)
    {
        if(! Gate::allows('is_child_model', $request->user())){
            abort(403, 'You should be a child!');
        }

        if(! Gate::allows('is_childs_regular_task', $regularTask)){
            abort(403, 'It is not your task!');
        }

        $validate = $request->validate([
            'is_timer_done' => 'sometimes|boolean',
            'is_before' => 'sometimes|boolean',
            'status' => ['sometimes', 'string', Rule::in(BaseController::TASK_STATUSES)]
        ]);
        
        $regularTask->update($validate);

        if ($request->hasFile('image_proof'))
        {
            $request->validate([
                'image_proof.*' => 'mimes:jpg,png,jpeg|max:5000'
            ], [
                'image_proof.*.mimes' => 'The file should be in one of the formats: png, jpg, jpeg',
            ]);
            
            $path = $request->file('image_proof')
                ->store('regular-tasks-proof-images', 'public');

            // Storage::disk('public')->delete($regularTask->imageProof->filename);

            $is_before = array_key_exists('is_before', $validate) ? 
                $validate['is_before'] : 
                null;
                    
            $regularTask->imageProof()->create([
                'filename' => $path,
                'is_before' => $is_before,
            ]);

            $regularTask->load('imageProof', 'regularTaskTemplate');   
        }
// TODO if you need you can attach event with listeners
            

        return $this->sendResponseWithData($regularTask, 200);
    }

    public function updateOneDayTask(Request $request, OneDayTask $oneDayTask)
    {
        if(! Gate::allows('is_child_model', $request->user())){
            abort(403, 'You should be a child!');
        }

        if(! Gate::allows('is_childs_one_day_task', $oneDayTask)){
            abort(403, 'It is not your task!');
        }

        $validated = $request->validate([
            'status' => ['sometimes', 'string', Rule::in(BaseController::TASK_STATUSES)],
            'is_timer_done' => 'sometimes|boolean',
            'is_before' => 'sometimes|boolean',
        ]);

        $oneDayTask->update($validated);

        if ($request->hasFile('image_proof'))
            {
                
                $request->validate([
                    'image_proof.*' => 'mimes:jpg,png,jpeg|max:5000'
                ], [
                    'image_proof.*.mimes' => 'The file should be in one of the formats: png, jpg, jpeg',
                ]);
                
                $path = $request->file('image_proof')
                    ->store('one-day-tasks-proof-images', 'public');

                // Storage::disk('public')->delete($regularTask->imageProof->filename);

                $is_before = array_key_exists('is_before', $validated) ? 
                    $validated['is_before'] : 
                    null;
                
                $oneDayTask->imageProof()->create([
                    'filename' => $path,
                    'is_before' => $is_before,
                ]);

                $oneDayTask->imageProof;   
            }

        return $this->sendResponseWithData($oneDayTask, 200);
    }
}
