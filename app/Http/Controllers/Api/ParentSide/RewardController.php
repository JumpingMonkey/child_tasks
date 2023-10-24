<?php

namespace App\Http\Controllers\Api\ParentSide;

use App\Http\Controllers\Api\BaseController;
use App\Models\Child;
use App\Models\ChildReward;
use App\Models\ChildRewardImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class RewardController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, Child $child)
    {
        if(! Gate::allows('is_related_adult', $child)){
            abort(403, 'It is not your child!');
        }

        $filters = $request->only([
            'is_claimed',
            'is_received'
        ]);

        $success = $child
            ->rewards()
            ->filter($filters)
            ->get();

        return $this->sendResponseWithData($success, 200);
    }

    public function getRewardById(Request $request, Child $child, ChildReward $childReward)
    {
        return $this->sendResponseWithData($childReward->load('image'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Child $child)
    {
        if(! Gate::allows('is_related_adult', $child)){
            abort(403, 'It is not your child!');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:50',
            'price' => 'required|integer|max:2000',
        ]);
        
        $validated['status'] = 'active';

        $reward = new ChildReward($validated);

        $reward->child()->associate($child);
        $reward->adult()->associate($request->user());
        $reward->save();

        if ($request->hasFile('image'))
        {
            $request->validate([
                'image.*' => 'mimes:jpg,png,jpeg|max:5000'
            ], [
                'image.*.mimes' => 'The file should be in one of the formats: png, jpg, jpeg',
            ]);
            
                $path = $request->file('image')
                    ->store('reward-images', 'public');

                $image = new ChildRewardImage([
                    'filename' => $path,
                ]);
                
                $reward->image()->save($image);
            
            return $this->sendResponseWithData($reward->withoutRelations()->load('image'), 200);
        } else {
            
            return $this->sendResponseWithData($reward->withoutRelations(), 200);
        } 
    }



    /**
     * Display the specified resource.
     */
    public function attachImage(Request $request, Child $child, ChildReward $childReward)
    {
        if(! Gate::allows('is_related_adult', $child)){
            abort(403, 'It is not your child!');
        }

        if ($request->hasFile('image'))
        {
            $request->validate([
                'image.*' => 'mimes:jpg,png,jpeg|max:5000'
            ], [
                'image.*.mimes' => 'The file should be in one of the formats: png, jpg, jpeg',
            ]);
            
            if($childReward->image()->exists()){
                Storage::disk('public')->delete($childReward->image->filename);
                $childReward->image->delete();
            }
            
                $path = $request->file('image')
                    ->store('reward-images', 'public');

                $image = new ChildRewardImage([
                    'filename' => $path,
                ]);
                
                $childReward->image()->save($image);
            
            return $this->sendResponseWithData($childReward->load(['image']), 200);
        } else {
            return $this->sendResponseWithOutData('Add image please!',);
        } 
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function detachImage(Request $request, Child $child, ChildReward $childReward)
    {
        if(! Gate::allows('is_related_adult', $child)){
            abort(403, 'It is not your child!');
        }

        $image = $childReward->image;

        Storage::disk('public')->delete($image->filename);
        $image->delete();

        return $this->sendResponseWithData($childReward->load(['image']), 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Child $child, ChildReward $childReward)
    {
        if(! Gate::allows('is_related_adult', $child)){
            abort(403, 'It is not your child!');
        }

        $validated = $request->validate([
            'title' => 'sometimes|string|max:50',
            'price' => 'sometimes|integer|max:2000',
        ]);
        $childReward->updateOrFail($validated);

        if ($request->hasFile('image'))
        {
            $request->validate([
                'image.*' => 'mimes:jpg,png,jpeg|max:5000'
            ], [
                'image.*.mimes' => 'The file should be in one of the formats: png, jpg, jpeg',
            ]);

            Storage::disk('public')->delete($childReward->image->filename);
            
            $path = $request->file('image')
                    ->store('reward-images', 'public');

            $childReward->image->update(['filename' => $path]);
            
            return $this->sendResponseWithData($childReward->load(['image']), 200);
        } else {
            return $this->sendResponseWithData($childReward->withoutRelations(), 200);
        } 
       
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Child $child, ChildReward $childReward)
    {
        if(! Gate::allows('is_related_adult', $child)){
            abort(403, 'It is not your child!');
        }

        if($childReward->image){
            $image = $childReward->image;

            Storage::disk('public')->delete($image->filename);
            $image->delete();
        }
        
        $childReward->delete();

        $result = $child->rewards()->get();

        return $this->sendResponseWithData($result, 200);

    }
}
