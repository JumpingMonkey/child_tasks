<?php

namespace App\Http\Controllers\Api\ChildrenSide;

use App\Events\ChildRewardWasClaimed;
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
 *
 * @param Request $request The HTTP request object
 * @param ChildReward $childReward The child reward object
 * @return \Illuminate\Http\Response
 */
public function updateReward(Request $request, ChildReward $childReward)
{
    // Check if the user is a child
    if (!Gate::allows('is_child_model', $request->user())) {
        abort(403, 'You should be a child!');
    }

    // Check if the reward belongs to the child
    if (!Gate::allows('is_childs_reward', $childReward)) {
        abort(403, 'It is not your reward!');
    }

    // Validate the request data
    $validated = $request->validate([
        'is_claimed' => 'sometimes|boolean'
    ]);

    // Check if the reward is already claimed
    if ($childReward->is_claimed) {
        return $this->sendError('', "Reward is already claimed", 401);
    }

    // Check if the child has enough coins to buy the reward
    if ($childReward->child->coins < $childReward->price) {
        return $this->sendError('', "You can't buy the reward. You have less coins than you need!", 401);
    }
    
    // Update the child reward with the validated data
    $childReward->update($validated);

    // Dispatch an event if the reward is claimed
    ChildRewardWasClaimed::dispatchIf($childReward->is_claimed, $childReward);      

    // Return a response with the updated child reward
    return $this->sendResponseWithData($childReward, 200);
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
