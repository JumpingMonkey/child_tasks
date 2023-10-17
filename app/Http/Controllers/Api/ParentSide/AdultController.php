<?php

namespace App\Http\Controllers\Api\ParentSide;

use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Controller;
use App\Models\Adult;
use Illuminate\Http\Request;

class AdultController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $adult = $request->user();

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
        $adult = $request->user();

        return $this->sendResponseWithData($adult, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Adult $adult)
    {
        $validated = $request->validate([
            'adult_type' => 'required|string|max:255',
        ]);

        $adult->update($validated);

        return $this->sendResponseWithData($adult, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
