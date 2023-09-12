<?php

namespace App\Http\Controllers\Api\ParentSide;

use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Controller;
use App\Models\Child;
use App\Models\RegularTask;
use App\Models\RegularTaskTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class RegularTaskController extends BaseController
{
//Todo create regular task template controller
    public function getGeneralAvalableTaskTemplates(Request $request)
    {
        $success = RegularTaskTemplate::
            select(['title', 'description', 'icon', 'id'])
            ->where('is_general_available', true)
            ->get();

        return $this->sendResponseWithData($success, 200);
    }

    public function storeRegularTasks(Request $request, Child $child)
    {
        if (! Gate::allows('is_related_adult', $child)) {
            abort(403, "Unauthorized");
        }
        //Todo write general available tasks checking
        foreach($request->get('tasks') as $task){
            
            $regularTask = RegularTask::make();
            $regularTask->regular_task_template_id = $task['template_id'];
            $regularTask->child_id = $child->id;
            $regularTask->coins = $task['coins'];
            $regularTask->status = "new";
            $regularTask->adult_id = $request->user()->id;
            $regularTask->save();
        }

        return $this->sendResponseWithOutData();
    }

    public function getRegularTasksByChild(Request $request, Child $child)
    {
        if (! Gate::allows('is_related_adult', $child)) {
            abort(403, "Unauthorized");
        }

        $result = $child->regularTasks()->get();

        return $this->sendResponseWithData($result, 200);
    }

    public function getRegularTasksTemplatesByChildId(Request $request, Child $child)
    {
        if (! Gate::allows('is_related_adult', $child)) {
            abort(403, "Unauthorized");
        }

        // $tasks = $child->regularTasks()->with('regularTaskTemplate')->get();
//Todo add showing coins in child regular tasks
        $active = RegularTaskTemplate::whereHas('regularTask', function($q) use($child){
            $q->whereHas('child', function($query) use($child){
                $query->where('child_id', $child->id);
            });
        })
        ->select(['title', 'description', 'icon', 'id', 'coins'])
        ->get();

        $inactive = RegularTaskTemplate::
        whereNotIn('id', $active->pluck('id'))
        ->select(['title', 'description', 'icon', 'id', 'coins'])
        ->get();
        
        $success = ['active' => $active, 'inactive' => $inactive];

        return $this->sendResponseWithData($success, 200);
    }

    public function updateTaskReward(Request $request, Child $child, int $id)
    {
        
        if (! Gate::allows('is_related_adult', $child)) {
            abort(403, "Unauthorized");
        }
        $validated = $request->validate([
            'coins' => 'required|int|max:6'
        ]);
        $task = $child->regularTasks()->findOrFail($id);
        $task->update($validated);

        return $this->sendResponseWithData($task);
    }

    public function destroyRegularTasksByChild(Request $request, Child $child)
    {
        if (! Gate::allows('is_related_adult', $child)) {
            abort(403, "Unauthorized");
        }

        $child->regularTasks()
            ->findOrFail(14)
            ->delete();

        return $this->sendResponseWithOutData();
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
     * Store a newly created resource in storage.
     */
    public function storeCustomRegularTask(Request $request)
    {
        
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
