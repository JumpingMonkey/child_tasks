<?php

namespace App\Http\Controllers\Api\ParentSide;

use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Controller;

use App\Models\Adult;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Socialite\Facades\Socialite;

class SocialLoginController extends BaseController
{
    const PROVIDERS = ['google'];
    
    public function redirectToProvider($provider)
    {
        $validated = Validator::make(['provider' => $provider], [
            "provider" => [
                'required',
                Rule::in(self::PROVIDERS),
            ],
            
        ])->validated();

        
        return Socialite::driver($validated['provider'])->stateless()->redirect();

    }

    public function handleProviderCallback($provider)
    {
        try {
            $user = Socialite::driver($provider)->stateless()->user();
        } catch (ClientException $clientException) {
            return response()->json(['message'=> 'Invalid credentials'],422);
        }

            $createdUser = Adult::query()->firstOrCreate(
            [
                'email' => $user->getEmail(),
                
            ], [
                'email_verified_at' => now(),
                'name' => $user->getName(),
            ]
            );

            $createdUser->socialProviders()->updateOrCreate(
                [
                    'provider' => $provider,
                    'provider_id' => $user->getId(),
                ]
            );

            $success['token'] =  $createdUser->createToken('MyApp')->plainTextToken;
            $success['email'] =  $createdUser->email;
            $success['id'] = $createdUser->id;
            
            return $this->sendResponseWithData($success, 201);
    }
}
