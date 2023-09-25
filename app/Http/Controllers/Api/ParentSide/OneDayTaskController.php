<?php

namespace App\Http\Controllers\Api\ParentSide;

use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Controller;
use App\Models\Child;
use App\Models\OneDayTask;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class OneDayTaskController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, Child $child)
    {
        if (! Gate::allows('is_related_adult', $child)) {
            abort(403, "Unauthorized");
        }

        $result = OneDayTask::where('child_id', $child->id)->get();

        return $this->sendResponseWithData($result);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Child $child)
    {
        if (! Gate::allows('is_related_adult', $child)) {
            abort(403, "Unauthorized");
        }

        $validated = $request->validate([
            'title' => 'required|string|max:100',
            'description' => 'required|string|max:500',
            'coins' => 'required|max:300|integer',
            'expected_duration' => "sometimes|integer",
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'proof_type_id' => 'required|integer',
        ]);
        
        $validated['child_id'] = $child->id;
        $validated['adult_id'] = $request->user()->id;
    
        $result = OneDayTask::create($validated);

        return $this->sendResponseWithData($result);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Child $child, OneDayTask $oneDayTask)
    {
        if (! Gate::allows('is_related_adult', $child) || 
            ! Gate::allows('is_related_one_day_task', [$oneDayTask, $child])) {
            abort(403, "Unauthorized");
        }
        
        $result = $oneDayTask->load(['timer', 'proofType']);

        return $this->sendResponseWithData($result);
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
