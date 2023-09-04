<?php

namespace App\Http\Controllers\Api\ParentSide;

use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Controller;
use App\Models\Child;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ChildController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $success = $request->user()->children()->get();
        return $this->sendResponseWithData($success, 200);
    }

    public function getAccessTokenByChild(Request $request, Child $child)
    {
        if (! Gate::allows('is_related_adult', $child)) {
            abort(403, "Unauthorized");
        }
        $success['token'] =  $child->createToken('MyApp')->plainTextToken;
        $success['name'] =  $child->name;
        return $this->sendResponseWithData($success);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => "required|string|max:50",
            'age' => "required|integer|max:20",
            'gender' => "required|boolean",
            'adult_type' => "required|string|max:50",
        ]);

        $child = Child::create($validated);
        $request->user()->children()->attach($child->id, ['adult_type' => $validated['adult_type']]);
        
        $success['token'] =  $child->createToken('MyApp')->plainTextToken;
        $success['name'] =  $child->name;
        
        return $this->sendResponseWithData($success, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Child $child)
    {
        if (! Gate::allows('is_related_adult', $child)) {
            abort(403, "Unauthorized");
        }
        return $this->sendResponseWithData($child);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Child $child)
    {
        if (! Gate::allows('is_related_adult', $child)) {
            abort(403, "Unauthorized");
        }
        $validated = $request->validate([
            'name' => "required|string|max:50",
            'age' => "required|integer|max:20",
            'gender' => "required|boolean",
        ]);

        $child->update($validated);
        
        return $this->sendResponseWithData($child, 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Child $child)
    {
        if (! Gate::allows('is_related_adult', $child)) {
            abort(403, "Unauthorized");
        }
        $child->adults()->detach();
        $child->delete();

        return $this->sendResponseWithoutData('Child was deleted!');
    }
}
