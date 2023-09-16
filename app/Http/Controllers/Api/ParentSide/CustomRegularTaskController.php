<?php

namespace App\Http\Controllers\Api\ParentSide;

use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Controller;
use App\Models\Child;
use App\Models\RegularTaskTemplate;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class CustomRegularTaskController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, Child $child)
    {
        if(! Gate::allows('is_related_adult', $child)){
            abort(403, 'It is not your child!');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:100',
            'description' => 'required|string|max:500',
            'coins' => 'required|max:300|integer',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    //Todo Add icon and image
    public function storeCustomRegularTaskTemplate(Request $request, Child $child)
    {
        if (! Gate::allows('is_related_adult', $child)) {
            abort(403, "Unauthorized");
        }
        
        $validated = $request->validate([
            'title' => 'required',
            'description' => 'required',
            'coins' => 'required|int',
            'expected_duration' => 'sometimes|int',
            'proof_type_id' => "required|int",
            "schedule" => "required|array",
        ]);
        
        $schedule = Schedule::query()->firstOrCreate($validated['schedule']);

        $regularTaskTemplate = RegularTaskTemplate::query()->make($validated);
        $regularTaskTemplate->adult_id = $request->user()->id;
        $regularTaskTemplate->child_id = $child->id;
        $regularTaskTemplate->proof_type_id;
        $regularTaskTemplate->schedule_id = $schedule->id;
        $regularTaskTemplate->save();

        return $this->sendResponseWithData($regularTaskTemplate, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateCustomRegularTaskTemplate(Request $request, Child $child, RegularTaskTemplate $regularTaskTemplate)
    {
        if (! Gate::allows('is_related_adult', $child)) {
            abort(403, "Unauthorized");
        }

        $validated = $request->validate([
            'title' => 'sometimes|string',
            'description' => 'sometimes|string',
            'coins' => 'sometimes|integer',
            'expected_duration' => 'sometimes|integer',
            'proof_type_id' => "sometimes|integer",
            "schedule" => "sometimes|array",
        ]);
 
        $schedule = Schedule::query()->firstOrCreate($validated['schedule']);
        $validated['schedule_id'] = $schedule->id;

        $regularTaskTemplate->update($validated);

        return $this->sendResponseWithData($regularTaskTemplate, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroyCustomRegularTskTemplate(Request $request, Child $child, RegularTaskTemplate $regularTaskTemplate)
    {
        if (! Gate::allows('is_related_adult', $child) || 
            ! Gate::allows('is_related_regular_task', [$regularTaskTemplate, $child])) {
            abort(403, "Unauthorized");
        }

        $regularTaskTemplate->delete();
        $result = $child->regularTaskTemplates()->get();

        return $this->sendResponseWithData($result, 200);
    }
}
