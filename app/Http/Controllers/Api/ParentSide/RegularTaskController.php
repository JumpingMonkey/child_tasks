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
                "status" =>  "required|boolean"
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

    
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // $success = $request->user()->regularTasks()
        //     ->with([
        //         'child',
        //         'adult',
        // ])
        // ->with('regularTaskTemplate', function($regularTaskTemplate){
        //     $regularTaskTemplate->with([
        //         'proofType',
        //         'schedule'
        //     ]);
        // })
        // ->get();
        
        // return $this->sendResponseWithData($success, 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
