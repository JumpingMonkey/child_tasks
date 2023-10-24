<?php

namespace App\Http\Controllers\Api\ParentSide;

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
                "is_active" =>  "sometimes|boolean"
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
        if (! Gate::allows('is_related_adult', $child)) {
            abort(403, "Unauthorized");
        }

        $result = $child->regularTaskTemplates()->with('schedule', 'image')->get();

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
        ->with(['regularTaskTemplate.image'])
        ->get();
            

        return $this->sendResponseWithData($result, 200);
    }

}
