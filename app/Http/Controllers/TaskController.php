<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskStatus;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filters = $request->only([
            'status',
        ]);

        $statuses = TaskStatus::all();

        $tasks = $request->user()
            ->tasksForUser()
            ->filter($filters)
            ->latest()
            ->with('executor', 'status', 'images')
            ->get();
        
        return inertia('Tasks/Index', [
            'tasks' => $tasks,
            'filters' => $filters,
            'statuses' => $statuses,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        $task->status;
        return inertia('Tasks/Show', [
            'task' => $task,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task)
    {
        $task = $task->load('executor', 'creator.children', 'status');

        $statuses = TaskStatus::where('only_parent', false)->get();
        
        return inertia('Tasks/Edit', [
            'task' => $task,
            'statuses' => $statuses,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task)
    {
        $validated = $request->validate([
            'task_status_id' => 'required|integer',
        ]);
        
        $task->update($validated);

        return redirect()->route('child.tasks.index')->with('success', 'Task was updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
