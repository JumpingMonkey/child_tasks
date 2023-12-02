<?php

namespace App\Http\Controllers\Api\ParentSide;

use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Controller;
use App\Models\Adult;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class AdultController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function showAdultProfile(Request $request)
    {
        if(!Gate::allows('is_adult_model', $request->user())){
            abort(403,'You are not adult!');
        }
        
        // $adult = $request->user();

        $adult = Adult::where('id', $request->user()->id)
            ->with('adultType', 'accountSettings')
            ->firstOrFail()->toArray();
        
        if(isset($adult['adult_type'])){
            $adult['adult_type'] = $request->user()->adultType->translateModel();
        }
        
        return $this->sendResponseWithData($adult, 200);
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
        if(!Gate::allows('is_adult_model', $request->user())){
            abort(403,'You are not adult!');
        }

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
        $adultType = $user->adultType()->first();
        $user['adult_type'] = $adultType->translateModel();

        return $this->sendResponseWithData($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateAdultProfile(Request $request)
    {
        if(!Gate::allows('is_adult_model', $request->user())){
            abort(403,'You are not adult!');
        }

        $adult = $request->user();
        
        $validated = $request->validate([
            'adult_type_id' => 'sometimes|int',
            'name' => 'sometimes|string',
            'is_premium' => "sometimes|boolean",
            'email' => ['sometimes',
                'email', 
                Rule::unique('adults')->ignore($adult->id),
            ],
            // 'is_premium'=> 'sometimes|boolean',
            // 'until'=> 'sometimes|string',
        ]);

        $adult->update($validated);
        $adultType = $adult->adultType()->first();
        $adult['adult_type'] = $adultType->translateModel();

        return $this->sendResponseWithData($adult, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        if(!Gate::allows('is_adult_model', $request->user())){
            abort(403,'You are not adult!');
        }
//Todo sort of deleting process
        $request->user()->delete();

        return $this->sendResponseWithoutData();
    }
}
