<?php

namespace App\Http\Controllers\Api\ParentSide;

use App\Events\RegularTaskStatusWasUpdated;
use App\Events\RegularTaskTemplateStatusWasUpdated;
use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Controller;
use App\Models\Child;
use App\Models\RegularTask;
use App\Models\RegularTaskTemplate;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class RegularTaskController extends BaseController
{

    public function updateRegularTaskTemplates(Request $request, Child $child)
    {
        if (! Gate::allows('is_related_adult', $child)) {
            abort(403, "Unauthorized");
        }
        
        $updatedTasksIds = [];
        foreach($request->get('task_templates') as $taskTemplate){

            $validated = Validator::make($taskTemplate, [
                "task_template_id" =>  "required|integer",
                "coins" =>  "sometimes|integer",
                "is_active" =>  "sometimes|boolean",
                'is_unlock_required' => "sometimes|boolean",
            ])->validate();

            $updatedTasksIds[] = $taskTemplate['task_template_id'];
    
            $regTaskTemp = RegularTaskTemplate::findOrFail($taskTemplate['task_template_id']);

            $regularTaskStatusBefore = $regTaskTemp->is_active;

            if(! ($regTaskTemp->child_id == $child->id)){
                abort(403, "Unauthorized. Regular task template id:{$taskTemplate['task_template_id']} is not your own!");
            }

            $regTaskTemp->update($validated);
            
            if($regTaskTemp->is_active && $regularTaskStatusBefore != $regTaskTemp->is_active){
                RegularTaskTemplateStatusWasUpdated::dispatch($regTaskTemp);
            }
        }

        $updatedTasks = RegularTaskTemplate::query()->whereIn('id', $updatedTasksIds)->get();

        return $this->sendResponseWithData($updatedTasks->load('schedule'));
    }

    public function getRegularTaskTemplatesByChild(Request $request, Child $child)
    {
        if (! Gate::allows('is_adult_model')) {
            abort(403, "Unauthorized");
        }

        if (! Gate::allows('is_related_adult', $child)) {
            abort(403, "Unauthorized");
        }
        
        $filters = $request->only(['is_unlock_required']);
        
        $result = $child->regularTaskTemplates()
            ->filter($filters)
            ->with('schedule', 'image', 'taskIcon')->get();

        return $this->sendResponseWithData($result, 200);
    }

    public function getRegularTasksByChild(Request $request, Child $child)
    {
        if (! Gate::allows('is_related_adult', $child)) {
            abort(403, "Unauthorized");
        }

        $filters = $request->only(['status']);

        $result = RegularTask::query()->whereHas('regularTaskTemplate', function($q) use($child) {
            return $q->where('child_id', $child->id);
        })
        ->filter($filters)
        ->with(['regularTaskTemplate.image', 'regularTaskTemplate.proofType', 'imageProof', 'regularTaskTemplate.taskIcon'])
        ->get();
            

        return $this->sendResponseWithData($result, 200);
    }

    public function updateRegularTask(Request $request, RegularTask $regularTask)
    {
        
        if (! Gate::allows('is_related_regular_task', $regularTask->regularTaskTemplate)) {
            abort(403, "Unauthorized");
        }
        $oldStatus = $regularTask->status;
        $validated = $request->validate([
            'status' => ['sometimes', 'string', Rule::in(BaseController::TASK_STATUSES)],
            'is_timer_done' => ['sometimes','integer'],
        ]);

        $regularTask->update($validated);
        
        if($oldStatus != $regularTask->status){
            RegularTaskStatusWasUpdated::dispatch($regularTask);
        }
        

        return $this->sendResponseWithData($regularTask->load('regularTaskTemplate.image', 'regularTaskTemplate.taskIcon'), 200);
    }

}
