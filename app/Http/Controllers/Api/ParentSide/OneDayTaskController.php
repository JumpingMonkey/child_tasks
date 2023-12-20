<?php

namespace App\Http\Controllers\Api\ParentSide;

use App\Models\Child;
use App\Models\TaskImage;
use App\Models\OneDayTask;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use App\Events\OneDayTaskStatusWasUpdated;
use App\Http\Controllers\Api\BaseController;

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

        $filters = $request->only(['status']);

        $result = OneDayTask::where('child_id', $child->id)
            ->filter($filters)
            ->with('image', 'proofType', 'imageProof')
            ->get();

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
            'description' => 'sometimes|string|max:500',
            'coins' => 'required|integer',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'proof_type_id' => 'required|integer',
            'expected_duration' => 'required|integer'
        ]);
        
        $validated['child_id'] = $child->id;
        $validated['adult_id'] = $request->user()->id;
        
        $validated['status'] = BaseController::TASK_STATUSES[0];

        $oneDayTask = OneDayTask::create($validated);

        if ($request->hasFile('image'))
        {
            $request->validate([
                'image.*' => 'mimes:jpg,png,jpeg|max:5000'
            ], [
                'image.*.mimes' => 'The file should be in one of the formats: png, jpg, jpeg',
            ]);
            
                $path = $request->file('image')
                    ->store('one-day-tasks-images', 'public');

            $image = new TaskImage([
                'filename' => $path
            ]);
            
            $oneDayTask->image()->save($image);
        }

        return $this->sendResponseWithData($oneDayTask->load('image'));
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
        
        $result = $oneDayTask->load(['timer', 'proofType', 'image']);

        return $this->sendResponseWithData($result);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Child $child, OneDayTask $oneDayTask)
    {
        if (! Gate::allows('is_related_adult', $child) || 
            ! Gate::allows('is_related_one_day_task', [$oneDayTask, $child])) {
            abort(403, "Unauthorized");
        }

        $validated = $request->validate([
            'title' => 'sometimes|string|max:100',
            'description' => 'sometimes|string|max:500',
            'coins' => 'sometimes|integer',
            'start_date' => 'sometimes|date',
            'end_date' => 'sometimes|date',
            'proof_type_id' => 'sometimes|integer',
            'is_timer_done' => 'sometimes|integer',
            'status' => ['sometimes', 'string', Rule::in(BaseController::TASK_STATUSES)],
        ]);
        $oldStatus = $oneDayTask->status;
        $oneDayTask->update($validated);

        if ($request->hasFile('image'))
        {
            $request->validate([
                'image.*' => 'mimes:jpg,png,jpeg|max:5000'
            ], [
                'image.*.mimes' => 'The file should be in one of the formats: png, jpg, jpeg',
            ]);
            
                $path = $request->file('image')
                    ->store('one-day-tasks-images', 'public');

                $oldPath = $oneDayTask?->image?->filename ?? '-';
                    
                if(Storage::disk('public')->exists($oldPath)){
                    
                    Storage::disk('public')->delete($oneDayTask->image->filename);
                    $oneDayTask->image->update([
                        'filename' => $path
                    ]);
                } else {
                    $oneDayTask->image()->create([
                        'filename' => $path
                    ]);
                }
        }

        if($oldStatus != $oneDayTask->status){
            OneDayTaskStatusWasUpdated::dispatch($oneDayTask);
        }

        $coins = $oneDayTask->child->coins;

        $task = $oneDayTask->load('image');

        $task = $task->toArray();
        $task['child_coins'] = $coins;

        return $this->sendResponseWithData($task);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Child $child, OneDayTask $oneDayTask)
    {
        if (! Gate::allows('is_related_adult', $child) || 
            ! Gate::allows('is_related_one_day_task', [$oneDayTask, $child])) {
            abort(403, "Unauthorized");
        }
        $oneDayTask->image?->delete();
        $oneDayTask->delete();
        
        return $this->sendResponseWithoutData('Task was deleted!');
    }
}