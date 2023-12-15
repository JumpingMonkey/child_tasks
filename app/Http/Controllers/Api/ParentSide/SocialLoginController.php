<?php

namespace App\Http\Controllers\Api\ParentSide;

use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Requests\SocialLoginRequest;
use App\Models\Adult;
use Google\AccessToken\Verify;
use Google\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Socialite\Facades\Socialite;

class SocialLoginController extends BaseController
{
    const PROVIDERS = ['google'];
    
    ////////WEB/////////
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
            // dd($user);
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

    /**
     * Android and Apple Social Login
     */
    public function appSocialLogin(SocialLoginRequest $request)
    {
        $validated = $request->validated();
        $token = $validated['access_token'];
        $provider = $validated['provider'];

        if ($request->provider === 'google' && $request->platform === 'android') {
            
            $providerUser = new Client(['client_id' => env('GOOGLE_ANDROID_CLIENT_ID')]);
            
            $providerUser = $providerUser->verifyIdToken($token);
            if(!$providerUser){
                return $this->sendError([], 'Invalid token!');
            }
        }

        if ($request->provider === 'google' && $request->platform === 'apple') {
            
            $providerUser = new Client(['client_id' => env('GOOGLE_IOS_CLIENT_ID')]);

            $providerUser = $providerUser->verifyIdToken($token);
            if(!$providerUser){
                return $this->sendError([], 'Invalid token!');
            }
        }


        if ($request->provider === 'apple' && $request->platform === 'apple') {
            config([
                "services.$request->provider.client_id" => env('APPLE_CLIENT_ID'),
            ]);

            $providerUser = Socialite::driver($provider)->stateless()->userFromToken($token);
                    }        

        $createdUser = Adult::query()->firstOrCreate(
            [
                'email' => $providerUser['email'],
                
            ], [
                'email_verified_at' => now(),
                'name' => $providerUser['name'] ?? $request->name,
            ]
        );
        $createdUser->socialProviders()->updateOrCreate(
            [
                'provider' => $provider,
                'provider_id' => $providerUser['sub'],
            ]
        );
        // if there is no record with these data, create a new user
        
        // create a token for the user, so they can login
        $success['token'] =  $createdUser->createToken('Google')->plainTextToken;
        $success['email'] =  $createdUser->email;
        $success['id'] = $createdUser->id;
        // return the token for usage   
        return $this->sendResponseWithData($success, 201);
    }
     
}
