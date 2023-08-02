<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ChildTaskImageController extends Controller
{
    public function create(Task $task)
    {
        
        $task->load('images');
        
        return inertia('Tasks/Image/Create',
            [
                'task' => $task,
            ]
        );
    }

    public function store(Request $request, \App\Models\Task $task)
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

                $task->images()->save(new Image([
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
