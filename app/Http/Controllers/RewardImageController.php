<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\Reward;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RewardImageController extends Controller
{
    public function create(Reward $reward)
    {
        $reward->load('images');
        
        return inertia('Rewards/Image/Create',
            [
                'reward' => $reward,
            ]
        );
    }

    public function store(Request $request, Reward $reward)
    {

        if ($request->hasFile('images'))
        {
            $request->validate([
                'images.*' => 'mimes:jpg,png,jpeg|max:5000'
            ], [
                'images.*.mimes' => 'The file should be in one of the formats: png',
            ]);

            foreach($request->file('images') as $file)
            {
                $path = $file->store('images', 'public');

                $reward->images()->save(new Image([
                    'filename' => $path,
                ]));
            }
            return redirect()->back()->with('success', 'Files were uploaded!');
        } else {
            return redirect()->back()->with('success', 'Fuck!');
        }
    }

    public function destroy($listing, \App\Models\Image $image)
    {
        Storage::disk('public')->delete($image->filename);
        $image->delete();

        return redirect()->back()->with('success', 'Image was deleted!');
    }


}
