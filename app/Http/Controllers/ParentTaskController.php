<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;


class ParentTaskController extends Controller
{
    public function index(Request $request)
    {
        $tasks = $request->user()
            ->createdTasks()
            ->latest()
            ->with('executor', 'status')->get();

        return inertia('ParentsTasks/Index', [
            'tasks' => $tasks,
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

        return redirect()->route('parents-tasks.index')->with('success', 'Task was created!');
    }

    public function show(Task $parents_task)
    {
        $task = $parents_task->load('executor', 'status');
        
        return inertia('ParentsTasks/Show', [
            'task' => $task,
        ]);
    }

    public function edit(Task $parents_task)
    {
        $task = $parents_task->load('executor', 'creator.children');

        return inertia('ParentsTasks/Edit', [
            'task' => $task,
        ]);
    }

    public function update(Request $request, Task $parents_task)
    {
        $validated = $request->validate([
            'title' =>  'required|string|max:255',
            'description' =>  'required|string:max:500',
            'coins' => 'required|integer|max:30000|min:1',
            'planned_and_date' =>  'required|date',
            'executor_id' =>  'required|integer',
            'is_image_required' =>  'required|boolean',
        ]);
        
        $request->user()->createdTasks()->where('id', $parents_task->id)->update($validated);

        return redirect()->route('parents-tasks.index')->with('success', 'Task was updated!');
    }

    public function destroy(Task $parents_task)
    {
        $parents_task->delete();

        return redirect()->route('parents-tasks.index')->with('success', 'Task was deleted!');
    }
}
