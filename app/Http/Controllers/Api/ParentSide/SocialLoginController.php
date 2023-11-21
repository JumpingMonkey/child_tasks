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

    /**
     * Android Social Login
     */
    public function androidSocialLogin(Request $request)
    {
        $provider = "google"; // or $request->input('provider_name') for multiple providers
        $token = $request->input('access_token');
        // get the provider's user. (In the provider server)
        $providerUser = Socialite::driver($provider)->userFromToken($token);
        
        // check if access token exists etc..
        // search for a user in our server with the specified provider id and provider name
        // $user = Adult::where('provider_name', $provider)->where('provider_id', $providerUser->id)->first();
        $createdUser = Adult::query()->firstOrCreate(
            [
                'email' => $providerUser->getEmail(),
                
            ], [
                'email_verified_at' => now(),
                'name' => $providerUser->getName(),
            ]
        );
        $createdUser->socialProviders()->updateOrCreate(
            [
                'provider' => $provider,
                'provider_id' => $providerUser->getId(),
            ]
        );
        // if there is no record with these data, create a new user
        if($user == null){
            $user = User::create([
                'provider_name' => $provider,
                'provider_id' => $providerUser->id,
            ]);
        }
        // create a token for the user, so they can login
        $success['token'] =  $createdUser->createToken('MyApp')->plainTextToken;
        $success['email'] =  $createdUser->email;
        $success['id'] = $createdUser->id;
        // return the token for usage   
        return $this->sendResponseWithData($success, 201);
    }
}
