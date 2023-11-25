<?php

namespace App\Http\Controllers\Api\ParentSide;

use App\Events\RegularTaskTemplateStatusWasUpdated;
use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Controller;
use App\Models\Child;
use App\Models\RegularTaskTemplate;
use App\Models\Schedule;
use App\Models\TaskImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class CustomRegularTaskController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function getCustomRegularTaskTemplateById(Request $request, RegularTaskTemplate $regularTaskTemplate)
    {
        // if(! Gate::allows('is_related_adult', $child)){
        //     abort(403, 'It is not your child!');
        // }

        return $this->sendResponseWithData($regularTaskTemplate, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    
    public function storeCustomRegularTaskTemplate(Request $request, Child $child)
    {
        if (! Gate::allows('is_related_adult', $child)) {
            abort(403, "Unauthorized");
        }
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'sometimes|string|max:500',
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
        $regularTaskTemplate->is_active = 1;
        $regularTaskTemplate->schedule_id = $schedule->id;
        $regularTaskTemplate->save();

        if ($request->hasFile('image'))
        {
            $request->validate([
                'image.*' => 'mimes:jpg,png,jpeg|max:5000'
            ], [
                'image.*.mimes' => 'The file should be in one of the formats: png, jpg, jpeg',
            ]);
            
                $path = $request->file('image')
                    ->store('regular-tasks-images', 'public');

                $image = new TaskImage([
                    'filename' => $path
                ]);

                $regularTaskTemplate->image()->save($image);
        }
        
        if($regularTaskTemplate->is_active){
            RegularTaskTemplateStatusWasUpdated::dispatch($regularTaskTemplate);
        }
        
        $regularTaskTemplate->load($regularTaskTemplate::REQUIRED_RELATIONSHIPS);

        return $this->sendResponseWithData($regularTaskTemplate, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateCustomRegularTaskTemplate(Request $request, Child $child, RegularTaskTemplate $regularTaskTemplate)
    {
        if (! Gate::allows('is_related_adult', $child) || 
            ! Gate::allows('is_related_regular_task_and_child', [$regularTaskTemplate, $child]) ||
            $regularTaskTemplate->is_general_available) {
            abort(403, "Unauthorized");
        }

        $validated = $request->validate([
            'title' => 'sometimes|string',
            'description' => 'sometimes|string',
            'coins' => 'sometimes|integer',
            'expected_duration' => 'sometimes|integer',
            'proof_type_id' => "sometimes|integer",
            "schedule" => "sometimes|array",
            "is_active" => "sometimes"
        ]);

        if($request->filled('schedule')){
            $schedule = Schedule::query()->firstOrCreate($validated['schedule']);
            $validated['schedule_id'] = $schedule->id;
        }
        
        $regularTaskTemplate->update($validated);
        
        if ($request->hasFile('image'))
        {
            $request->validate([
                'image.*' => 'mimes:jpg,png,jpeg|max:5000'
            ], [
                'image.*.mimes' => 'The file should be in one of the formats: png, jpg, jpeg',
            ]);
            
                
                $path = $request->file('image')
                    ->store('regular-tasks-images', 'public');

                $oldPath = $regularTaskTemplate?->image?->filename ?? '-';
                    
                if(Storage::disk('public')->exists($oldPath)){
                    
                    Storage::disk('public')->delete($regularTaskTemplate->image->filename);
                    $regularTaskTemplate->image->update([
                        'filename' => $path
                    ]);
                } else {
                    $regularTaskTemplate->image()->create([
                        'filename' => $path
                    ]);
                }
        }
        $regularTaskTemplate->load($regularTaskTemplate::REQUIRED_RELATIONSHIPS);

        return $this->sendResponseWithData($regularTaskTemplate, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroyCustomRegularTaskTemplate(Request $request, Child $child, RegularTaskTemplate $regularTaskTemplate)
    {
        if (! Gate::allows('is_related_adult', $child) || 
            ! Gate::allows('is_related_regular_task_and_child', [$regularTaskTemplate, $child]) ||
            $regularTaskTemplate->is_general_available) {
            abort(403, "Unauthorized");
        }
        $regularTaskTemplate->image?->delete();
        $regularTaskTemplate->delete();
        $result = $child->regularTaskTemplates()->get();

        return $this->sendResponseWithData($result, 200);
    }
}
