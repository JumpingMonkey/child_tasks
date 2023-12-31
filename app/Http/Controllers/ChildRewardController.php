<?php

namespace App\Http\Controllers;

use App\Models\Reward;
use Illuminate\Http\Request;

class ChildRewardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $userRewards = $request->user()
            ->rewards()
            ->with('user', 'images')
            ->get();
        $parentRewards = $request->user()
            ->parent
            ->rewards()
            ->with('user', 'images')
            ->get();
        $result = $userRewards->merge($parentRewards);
        //Todo Do sorting!
        return inertia('ChildReward/Index', [
            'rewards' => $result,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        return inertia('ChildReward/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|max:100|string',
            'description' => 'required|string',
            'price' => 'required|integer|max:100',
        ]);

        $reward = Reward::make($validated);

        $reward->user()->associate($request->user());

        $reward->save();

        return redirect()->route('child.rewards.index')->with('success', 'Reward was created!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Reward $reward)
    {
        return inertia('ChildReward/Show', [
            'reward' => $reward->load(['user']),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Reward $reward)
    {
        
        return inertia('ChildReward/Edit', [
            'reward' => $reward,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Reward $reward)
    {
        $validated = $request->validate([
            'title' => 'required|max:100|string',
            'description' => 'required|string',
            'price' => 'required|integer',
            
        ]);
        
        $reward->update($validated);

        return redirect()->route('child.rewards.index')->with('success', 'Reward was updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Reward $reward)
    {
        $reward->deleteOrFail();
        return redirect()->route('child.rewards.index')->with('success', 'Reward was deleted!');
    }

    /*
    * Make the reward claimed
    */
    public function makeClaime(Request $request, Reward $reward)
    {
        $reward->claimedBy()->associate($request->user());
        $reward->setDataTimeAsCurentDatePlusNumbersOfDays('claimed_by_date');
        $reward->save();

        return redirect()->back()->with('success', 'Reward was claimed');
    }
}
