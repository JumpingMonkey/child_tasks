<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


class ParentTaskController extends Controller
{
    public function index(Request $request)
    {
        $tasks = $request->user()->createdTasks()->with('executor')->get();

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
}
