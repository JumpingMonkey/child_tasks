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

    const TASK_STATUSES = [
        'should do',
        'done',
        'redo the task'
    ];


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
    
            RegularTaskTemplate::findOrFail($taskTemplate['task_template_id'])
                ->update($validated);
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
}
