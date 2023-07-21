<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class ChildrenController extends Controller
{
    public function index(Request $request)
    {
        $children = $request->user()
            ->children()
            ->get();
            
        return inertia('Children/Index', [
            'children' => $children,
        ]);
    }

    public function detouch(Request $request, User $child)
    {
        $child->parent()->disassociate();
        $child->save();

        return redirect()->intended()->with('success', 'Child was detouched!');
    }
}
