<?php

namespace App\Http\Controllers;

use App\Models\AttachCode;
use Illuminate\Http\Request;

class ParentController extends Controller
{
    public function index(Request $request)
    {
        $parent = $request->user()->parent;
        return inertia('Parents/Index', [
            'parent' => $parent,
        ]);
    }

    public function create(Request $request)
    {
        return inertia('Parents/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|integer|exists:attach_codes',
        ]);

        $res = AttachCode::where('code', $validated['code'])
        ->first()->user;
        
        $request->user()->parent()->associate($res);
        $request->user()->save();

        $destroy = AttachCode::where('code', $validated['code'])
        ->first();

        $destroy->delete();
        
        return redirect()->route('child.parent.index')->with('success', 'Parent was attached!');
    }
}
