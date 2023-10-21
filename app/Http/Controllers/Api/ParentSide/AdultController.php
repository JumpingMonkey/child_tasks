<?php

namespace App\Http\Controllers\Api\ParentSide;

use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Controller;
use App\Models\Adult;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdultController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function showAdultProfile(Request $request)
    {
        $adult = $request->user();

        return $this->sendResponseWithData($adult->load('adultType'), 200);
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
