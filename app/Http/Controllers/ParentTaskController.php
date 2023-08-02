<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskStatus;
use Illuminate\Http\Request;


class ParentTaskController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->only([
            'status',
        ]);

        $statuses = TaskStatus::all();

        $tasks = $request->user()
            ->createdTasks()
            ->filter($filters)
            ->latest()
            ->with('executor', 'status', 'images')
            ->get();

        return inertia('ParentsTasks/Index', [
            'tasks' => $tasks,
            'filters' => $filters,
            'statuses' => $statuses,
        ]);
    }

    public function create(Request $request)
    {
        
        $children = $request->user()->children()->get();
        
        return inertia('ParentsTasks/Create', [
            'children' => $children,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' =>  'required|string|max:255',
            'description' =>  'required|string:max:500',
            'coins' => 'required|integer|max:30000|min:1',
            'planned_and_date' =>  'required|date',
            'executor_id' =>  'required|integer',
            'is_image_required' =>  'required|boolean',
        ]);

        $request->user()->createdTasks()->create($validated);

        return redirect()->route('parent.tasks.index')->with('success', 'Task was created!');
    }

    public function show(Task $task)
    {
        $task = $task->load('executor', 'status');
        
        return inertia('ParentsTasks/Show', [
            'task' => $task,
        ]);
    }

    public function edit(Task $task)
    {
        $task = $task->load('executor', 'creator.children', 'status');

        $statuses = TaskStatus::all();

        return inertia('ParentsTasks/Edit', [
            'task' => $task,
            'statuses' => $statuses,
        ]);
    }

    public function update(Request $request, Task $task)
    {
        $validated = $request->validate([
            'title' =>  'required|string|max:255',
            'description' =>  'required|string:max:500',
            'coins' => 'required|integer|max:30000|min:1',
            'planned_and_date' =>  'required|date',
            'executor_id' =>  'required|integer',
            'is_image_required' =>  'required|boolean',
            'task_status_id' => 'required|integer',
        ]);
        
        $request->user()->createdTasks()->where('id', $task->id)->update($validated);

        return redirect()->route('parent.tasks.index')->with('success', 'Task was updated!');
    }

    public function destroy(Task $task)
    {
        $task->delete();

        return redirect()->route('parent.tasks.index')->with('success', 'Task was deleted!');
    }
}
