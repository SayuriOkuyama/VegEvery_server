<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\Enums\OAuthProviderEnum;
use App\Models\SocialAccount;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class SocialController extends Controller
{


  public function redirect(OAuthProviderEnum $provider)
  {
    return Socialite::driver($provider->value)->redirect()->getTargetUrl();
  }

  public function callback(string $provider, Request $request)
  {
    Log::debug($request);
    Log::debug($provider);
    $providerUser = Socialite::driver('google')->stateless()->user();
    // Log::debug($providerUser);
    $id = $providerUser->getId();
    Log::debug($id);
    $registeredSocialAccount = SocialAccount::where('provider_id', $providerUser->getId())->first();

    if ($registeredSocialAccount) {
      $user = User::find($registeredSocialAccount->user_id);
      Auth::login($user);

      $response = [
        "message" => "login",
        "user" => $user
      ];
    } else {
      $response = [
        "message" => "noRegistered",
        "providerUserData" => $providerUser
      ];
    }
    return response()->json($response);
  }
}
