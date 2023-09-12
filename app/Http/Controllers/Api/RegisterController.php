<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\ApiRegisterRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\Adult;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class RegisterController extends BaseController
{
    /**
     * Register api
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(ApiRegisterRequest $request)
    {
        $validated = $request->validated();
        $validated['password'] = bcrypt($validated['password']);
        $user = Adult::create($validated);

        $success['token'] =  $user->createToken('MyApp')->plainTextToken;
        $success['email'] =  $user->email;
        $success['id'] = $user->id;

        return $this->sendResponseWithData($success, 201);
    }

    /**
     * Login api
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $validate = $request->validate([
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
        ]);

        $user = Adult::where('email', $request->email)->first();
        if (! $user || ! Hash::check($request->password, $user->password)) {
            return $this->sendError('Unauthorised.', ['error'=>'Unauthorised'], 401);
        }

        $success['token'] =  $user->createToken('MyApp')->plainTextToken;
        $success['email'] =  $user->email;
        $success['id'] = $user->id;
        return $this->sendResponseWithData($success);
        
    }
}
