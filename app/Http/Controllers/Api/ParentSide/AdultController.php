<?php

namespace App\Http\Controllers\Api\ParentSide;

use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Controller;
use App\Models\Adult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class AdultController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function showAdultProfile(Request $request)
    {
        $adult = $request->user();

        return $this->sendResponseWithData($adult->load('adultType', 'accountSettings'), 200);
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
    public function show(Request $request)
    {
        
    }

    public function updateSettings(Request $request)
    {
        $user = $request->user();

        if(!Gate::allows('is_adult_model', $request->user())){
            abort(403,'You are not adult!');
        }

        $validated = $request->validate([
            'is_child_notification_enabled' => 'sometimes|boolean',
            'is_adult_notification_enabled' => 'sometimes|boolean',
            'language' => ['sometimes', Rule::in(['en', 'uk', 'ru'])],
        ]);

        $user->accountSettings->update($validated);
        
        return $this->sendResponseWithData($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateAdultProfile(Request $request)
    {
        $adult = $request->user();
        
        $validated = $request->validate([
            'adult_type_id' => 'sometimes|int',
            'name' => 'sometimes|string',
            'email' => ['sometimes',
                'email', 
                Rule::unique('adults')->ignore($adult->id),
            ],
            // 'is_premium'=> 'sometimes|boolean',
            // 'until'=> 'sometimes|string',
        ]);

        $adult->update($validated);

        return $this->sendResponseWithData($adult->load('adultType'), 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
