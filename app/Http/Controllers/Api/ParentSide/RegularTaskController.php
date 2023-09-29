<?php

namespace App\Http\Controllers\Api\ParentSide;

use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Controller;
use App\Models\Child;
use App\Models\RegularTask;
use App\Models\RegularTaskTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;

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
                "coins" =>  "required|integer",
                "is_active" =>  "required|boolean"
            ])->validate();

            $updatedTasksIds[] = $taskTemplate['task_template_id'];
    
            $regTaskTemp = RegularTaskTemplate::findOrFail($taskTemplate['task_template_id']);

            if(! ($regTaskTemp->child_id == $child->id)){
                abort(403, "Unauthorized. Regular task template id:{$taskTemplate['task_template_id']} is not your own!");
            }

            $regTaskTemp->update($validated);
        }

        $updatedTasks = RegularTaskTemplate::query()->whereIn('id', $updatedTasksIds)->get();

        return $this->sendResponseWithData($updatedTasks);
    }

    public function getRegularTaskTemplatesByChild(Request $request, Child $child)
    {
        if (! Gate::allows('is_related_adult', $child)) {
            abort(403, "Unauthorized");
        }

        $result = $child->regularTaskTemplates()->get();

        return $this->sendResponseWithData($result, 200);
    }

    public function getDoneTasksByChild(Request $request, Child $child)
    {
        if (! Gate::allows('is_related_adult', $child)) {
            abort(403, "Unauthorized");
        }

        $result = RegularTask::query()->whereHas('regularTaskTemplate', function($q) use($child) {
            return $q->where('child_id', $child->id);
        })
        ->where('status', 'done')
        ->with(['regularTaskTemplate'])
        ->get();
            

        return $this->sendResponseWithData($result, 200);
    }

}
