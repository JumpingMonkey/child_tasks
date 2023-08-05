<?php

namespace App\Http\Controllers;

use App\Models\Reward;
use Illuminate\Http\Request;

class RewardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $rewards = $request->user()
            ->rewards()->with('user', 'images')->get();
        
        $childrenRewards = $request->user()->children()->pluck('id');
            
        $res = Reward::whereIn('user_id', $childrenRewards)->with('user', 'images')->get();

        $res = $rewards->merge($res); 
            
        return inertia('Rewards/Index', [
            'rewards' => $res,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        return inertia('Rewards/Create');
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

        $request->user()->is_parent ? 
            $validated['status']  = 1
            : $validated['status'] = 0;

        $reward = Reward::make($validated);

        $reward->user()->associate($request->user());

        $reward->save();

        return redirect()->route('parent.rewards.index')->with('success', 'Reward was created!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Reward $reward)
    {
        return inertia('Rewards/Show', [
            'reward' => $reward->load(['user']),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Reward $reward)
    {
        
        return inertia('Rewards/Edit', [
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
            'status' => 'boolean',
        ]);
        
        $reward->update($validated);

        return redirect()->route('parent.rewards.index')->with('success', 'Reward was updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Reward $reward)
    {
        $reward->deleteOrFail();
        return redirect()->route('parent.rewards.index')->with('success', 'Reward was deleted!');
    }
}
