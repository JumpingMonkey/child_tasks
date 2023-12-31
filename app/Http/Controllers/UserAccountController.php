<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserAccountController extends Controller
{
    public function create()
    {
        return inertia('UserAccount/Create');
    }


    public function store(Request $request)
    {
        
        $validated = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
            'is_parent' => 'required|boolean',
        ]);

        $user = User::make($validated);
        $user->password = Hash::make($user->password);
        $user->save();


        Auth::login($user);


        return redirect()->route('index')->with('success', 'Account was created!');
    }
}

