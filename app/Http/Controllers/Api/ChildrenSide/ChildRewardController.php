<?php

namespace App\Http\Controllers\Api\ChildrenSide;

use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Controller;
use App\Models\ChildReward;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ChildRewardController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function getAllRewards(Request $request)
    {
        if(! Gate::allows('is_child_model', $request->user())){
            abort(403, 'You should be a child!');
        }

        $result = $request->user()->rewards()->get();

        return $this->sendResponseWithData($result, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateReward(Request $request, ChildReward $childReward)
    {
        if(! Gate::allows('is_child_model', $request->user())){
            abort(403, 'You should be a child!');
        }

        if(! Gate::allows('is_childs_reward', $childReward)){
            abort(403, 'It is not your reward!');
        }


        $validated = $request->validate([
            'is_claimed' => 'sometimes|boolean'
        ]);
        
        $childReward->update($validated);

        return $this->sendResponseWithData($childReward,200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
