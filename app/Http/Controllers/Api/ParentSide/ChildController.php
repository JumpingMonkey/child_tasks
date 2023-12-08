<?php

namespace App\Http\Controllers\Api\ParentSide;

use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Controller;
use App\Models\Child;
use App\Models\GeneralAvailableRegularTask;
use App\Models\GeneralAvailableRegularTaskTemplate;
use App\Models\RegularTaskTemplate;
use App\Models\TaskImage;
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
        $success['id'] = $child->id;
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
        ]);
        
        // $children = $request->user()->children();
        // if($children->count() > 4) {
        //     abort("You can't have more then 4 children", 403);
        // }
        $child = Child::create($validated);
        
        $request->user()->children()->attach($child->id);
       
        $success['token'] =  $child->createToken('MyApp')->plainTextToken;
        $success['name'] =  $child->name;
        $success['id'] = $child->id;
        //Todo define subscriber for children and listener for code below
        $generalAvailableRegularTasks = GeneralAvailableRegularTaskTemplate::where('is_active', 1)->get();
        $activeTaskCounter = 1;
        foreach($generalAvailableRegularTasks as $task){
            $attributes = $task->getAttributes();
            $attributes['title'] = json_decode($attributes['title'], true);
            $attributes['description'] = json_decode($attributes['description'], true);
            unset($attributes['created_at'], $attributes['updated_at'], $attributes['id']);
            $attributes['adult_id'] = $request->user()->id;
            $attributes['child_id'] = $child->id;

            if($activeTaskCounter < 4) {
                $attributes['is_active'] = 1;
                $activeTaskCounter++;
            } else {
                $attributes['is_active'] = 0;
            }
            $attributes['is_general_available'] = true;
            
            $taskTemplate = RegularTaskTemplate::create($attributes);
            
            if(isset($task->image)){
                $path = $task?->image?->filename;
                $taskTemplate->image()->create(['filename' => $path]);
            }
            
        }
        
        return $this->sendResponseWithData($success, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Child $child)
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
