<?php

namespace App\Http\Controllers\Api\ParentSide;

use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Controller;
use App\Models\RegularTask;
use App\Models\RegularTaskTemplate;
use Illuminate\Http\Request;

class RegularTaskController extends BaseController
{

    public function getGeneralAvalableTasks(Request $request)
    {
        $success = RegularTaskTemplate::
            select(['title', 'description', 'icon', 'id'])
            ->get();

        return $this->sendResponseWithData($success, 200);
    }

    public function storeGeneralAvalableTasks(Request $request)
    {
        foreach($request->tasks as $taskId){
            $regularTask = RegularTask::make();
            $regularTask->regular_task_template_id = $taskId;
            $regularTask->child_id = $request->get('child_id');
            $regularTask->status = "new";
            $regularTask->adult_id = $request->user()->id;
            $regularTask->save();
        }

        return $this->sendResponseWithOutData();
    }

    public function destroyGeneralAvalableTasks(Request $request)
    {
        RegularTask::destroy($request->get('tasks'));
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
    public function store(Request $request)
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
