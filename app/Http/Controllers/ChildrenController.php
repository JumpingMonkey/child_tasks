<?php

namespace App\Http\Controllers;

use App\Models\AttachCode;
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

    public function generateAttachCode(Request $request)
    {
        $code = $request->user()->code()->first();
        if(!$code){
            $code = AttachCode::create([
                'user_id' => $request->user()->id,
                'code' => random_int(10, 99) . $request->user()->id,
            ]);
        }
        return inertia('Children/Code', [
            'code' => $code,
        ]);
               
    }
}
