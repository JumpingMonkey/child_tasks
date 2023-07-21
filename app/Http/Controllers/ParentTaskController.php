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
}
