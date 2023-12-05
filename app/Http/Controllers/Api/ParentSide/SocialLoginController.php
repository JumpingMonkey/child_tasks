<?php

namespace App\Http\Controllers\Api\ParentSide;

use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Controller;

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
     * Android Social Login
     */
    public function appSocialLogin(Request $request)
    {
        $provider = $request->get("provider"); // or $request->input('provider_name') for multiple providers
        $platform = $request->get('platform');
        $token = $request->input('access_token');

        if ($request->provider === 'google' && $request->platform === 'android') {
            config([
                "services.$request->provider.client_id" => env('GOOGLE_ANDROID_CLIENT_ID'),
            ]);
            // print_r($provider);
            // die;
            
            // $token = Socialite::driver($provider)->getAccessTokenResponse($token);
            // dd(11);
        }

        if ($request->provider === 'google' && $request->platform === 'ios') {
            config([
                "services.$request->provider.client_id" => env('GOOGLE_IOS_CLIENT_ID'),
            ]);
        }
// dd(2);
        // $providerUser = Socialite::driver($provider)->stateless()->userFromToken($token);
        // $providerUser = Socialite::driver($provider)->verifyIdToken($token);
        // $providerUser = Socialite::driver($provider)->stateless()->user();
        // $providerUser = new Verify();

        //Verify ID Token with Google library
        $providerUser = new Client(['client_id' => env('GOOGLE_CLIENT_ID')]);
        $providerUser = $providerUser->verifyIdToken($token);
        if(!$providerUser){
            return $this->sendError([], 'Invalid token!');
        }

        // print_r($providerUser);
        // die;
        
        // get the provider's user. (In the provider server)
        
        
        
        
        // check if access token exists etc..
        // search for a user in our server with the specified provider id and provider name
        // $user = Adult::where('provider_name', $provider)->where('provider_id', $providerUser->id)->first();
        $createdUser = Adult::query()->firstOrCreate(
            [
                'email' => $providerUser['email'],
                
            ], [
                'email_verified_at' => now(),
                'name' => $providerUser['name'],
            ]
        );
        $createdUser->socialProviders()->updateOrCreate(
            [
                'provider' => $request->provider,
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

   //////////////Vlad/////////////////

    public function redirectToGoogle(OnlyProviderRequest $request)
    {
        config([
            "services.$request->provider.redirect" => url("/api/v1/auth/$request->provider/callback")
        ]);
        return Socialite::driver($request->provider)->stateless()->redirect();
    }

    public function handleCallback(
        SocialProvidersRequest $request,
        User                   $userModel,
        IdSocialNetwork        $idSocialNetwork,
        AuthService            $authService
    )
    {
        try {

        if ($request->provider === SocialEnum::GOOGLE->value && $request->platform === AppPlatformEnum::ANDROID_APP->value) {
            config([
                "services.$request->provider.client_id" => env('GOOGLE_ANDROID_APP_ID'),
            ]);
        }

        if ($request->provider === SocialEnum::GOOGLE->value && $request->platform === AppPlatformEnum::IOS_APP->value) {
            config([
                "services.$request->provider.client_id" => env('GOOGLE_IOS_APP_ID'),
            ]);
        }

        $column = $request->provider . '_id';

        $socialUser = Socialite::driver($request->provider)->stateless()
            ->userFromToken($request->socialToken);
        if ($socialUser->getName() !== null) {
            $name = $socialUser->getName();
        } else {
            $name = $request->name;
        }

        $email = $socialUser->getEmail();
        $socialId = $socialUser->getId();
        $socialIdUser = $idSocialNetwork->where($column, $socialId)->first();

        $findUser = User::where('email', $socialUser->getEmail())->first();


        if ($findUser?->deleted_at) {
            return redirect(url(config('app.front_url')) . '?error=user has been removed');
        }

        if ($findUser !== null || $socialIdUser !== null) {
            if ($findUser !== null) {
                $userId = $findUser->id;
                $token = $authService->getTokenForSocial($findUser);
            } else {
                $userId = $socialIdUser->user_id;
                $token = $authService->getTokenForSocial($socialIdUser);
            }
        } else {
            $avatar = $socialUser->getAvatar();
            $newUser = $userModel->createUser($email, null, $name);
            $userId = $newUser->id;
            $prefix = bin2hex(random_bytes(20));
            $fileName = $prefix . '.' . 'png';
            $path = $this->getSocialAvatar($avatar, $userId, $fileName);
            $newUser->saveAvatar($path);
            $token = $authService->getTokenForSocial($userModel);
        }
        $idSocialNetwork->updateOrCreateNetworks($column, $userId, $socialId);

        return self::successfulResponseWithData($token);


        } catch (Exception $e) {
            Log::error($e);

            return self::errorResponse('Internal server error');
        }
    }

    function getSocialAvatar($avatar, $id, $fileName)
    {
        if(!$avatar) {
            return null;
        }

        $path = 'users/' . $id . '/avatar/' . $fileName;

        Storage::disk('public')->put($path, file_get_contents($avatar));

        return $path;
    }
}
