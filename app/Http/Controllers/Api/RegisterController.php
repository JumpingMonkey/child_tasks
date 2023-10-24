<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\ApiRegisterRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\Adult;
use App\Models\PasswordResetToken;
use App\Notifications\ApiPasswordResetNotification;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
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
            'email' => 'required|email',
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

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return $this->sendResponseWithOutData('Tokens was deleted');
    }

    public function sendPasswordResetToken(Request $request)
    {
        $valid = $request->validate([
            'email' => 'required|email|exists:adults,email'
        ]);
        
        $user = Adult::query()->where('email', $valid['email'])->first();

        $oldPasswordResetToken = PasswordResetToken::where('email', $user->email)->first();
        $newPasswordResetToken = str_pad(random_int(1,9999), 4, "0", STR_PAD_LEFT);

        if(!$oldPasswordResetToken){
            
            PasswordResetToken::create([
                'email' => $user->email,
                'token' => $newPasswordResetToken
            ]);
        } else {
            $oldPasswordResetToken->update([
                'email' => $user->email,
                'token' => $newPasswordResetToken
            ]);
        }

        $user->notify(new ApiPasswordResetNotification($newPasswordResetToken));

        return $this->sendResponseWithOutData('Reset pasword token was sent to your email');

        // $status = Password::sendResetLink(
        //     $request->only('email')
        // );

        // if($status == Password::RESET_LINK_SENT){
        //     return $this->sendResponseWithOutData('Token was sent to user email');
        // }

        // throw ValidationException::withMessages([
        //     'email' => [trans($status)]
        // ]);
    }

    public function resetPassword(Request $request)
    {
        $valid = $request->validate([
            'token' => 'required|max:9999|exists:password_reset_tokens,token',
            'password' => 'required|confirmed',
        ]);

        $PasswordResetTokenRecord = PasswordResetToken::where('token', $valid['token'])->first();
        
        $user = Adult::where('email', $PasswordResetTokenRecord->email)->first();
        
        $user->update([
            'password' => Hash::make($valid['password']),
        ]);

        $user->tokens()->delete();
        $PasswordResetTokenRecord->delete();

        $success['token'] =  $user->createToken('MyApp')->plainTextToken;
        $success['email'] =  $user->email;
        $success['id'] = $user->id;
        return $this->sendResponseWithData($success);
    }
}
