<?php

namespace App\Http\Controllers;

use App\Enums\OAuthProviderEnum;
use App\Models\SocialAccount;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class AuthController extends Controller
{
  public function index(Request $request)
  {
    Log::debug($request->user());
    return $request->user();
  }

  public function register(Request $request)
  {
    if ($request->provider) {
      // ランダムな文字列を生成し、テーブルに存在する場合は作り直す
      do {
        $randomString = Str::random(15);
      } while (User::where('account_id', $randomString)->exists());

      $user = User::create([
        "account_id" => $randomString,
        'name' => $request->name,
        'vegetarian_type' => $request->vegetarian_type,
        'icon_url' => $request->icon["url"],
        'icon_storage_path' => $request->icon["storage_path"],
      ]);

      SocialAccount::create([
        "user_id" => $user->id,
        "provider" => $request->provider,
        "provider_id" => $request->provider_id
      ]);
    } else {
      $user = User::create([
        "account_id" => $request->account_id,
        'name' => $request->name,
        'password' => $request->password,
        'secret_question' => $request->secret_question,
        'answer_to_secret_question' => $request->answer_to_secret_question,
        'vegetarian_type' => $request->vegetarian_type,
        'icon_url' => $request->icon["url"],
        'icon_storage_path' => $request->icon["storage_path"],
      ]);
    }

    Log::debug($user);
    // Laravel Sanctumのトークンを発行
    $token = $user->createToken('sanctum_token')->plainTextToken;

    return response()->json(['token' => $token, "user" => $user], 200);
  }

  public function login(Request $request)
  {
    Log::debug($request);

    // social ログインの場合
    if ($request->provider) {
      $social_account = SocialAccount::where([
        ['provider_id', '=', $request->provider_id],
        ['provider', '=', $request->provider],
      ])->first();

      $user = User::find($social_account->user_id);

      // Laravel Sanctumのトークンを発行
      $token = $user->createToken('sanctum_token')->plainTextToken;

      return response()->json(['token' => $token, "user" => $user], 200);

      // 普通のログインの場合
    } else {

      $request->validate([
        'account_id' => 'required|string',
        'password' => 'required',
      ]);

      $user = User::where('account_id', $request->account_id)->first();

      if (!$user || !Hash::check($request->password, $user->password)) {
        return response()->json([
          'message' => 'ユーザー名かパスワードが間違っています'
        ], 401);
      }

      $token = $user->createToken('sanctum_token')->plainTextToken;

      return response()->json(['token' => $token, 'user' => $user], 200);
    }
  }

  public function logout(Request $request)
  {
    // 現在のアクセストークンを削除して特定のセッションをログアウト
    $request->user()->currentAccessToken()->delete();

    // レスポンスを返す
    return response()->json(['message' => 'ログアウトしました。'], 200);
  }

  public function redirect(OAuthProviderEnum $provider)
  {
    return Socialite::driver($provider->value)->redirect()->getTargetUrl();
  }

  /**
   * 新規登録、ログイン共通
   * ソーシャルアカウントが登録済みか確認
   */
  public function callback(string $provider, Request $request)
  {
    Log::debug($request);
    Log::debug($provider);
    $providerUser = Socialite::driver('google')->stateless()->user();

    $registeredSocialAccount = SocialAccount::where([
      ['provider_id', '=', $providerUser->getId()],
      ['provider', '=', $provider],
    ])->first();

    $response = "";
    if ($registeredSocialAccount) {

      $response = [
        "message" => "registered",
        "socialAccountId" => $registeredSocialAccount->provider_id
      ];
    } else {
      $response = [
        "message" => "noRegistered",
        "socialUser" => $providerUser,
      ];
    }
    return response()->json($response);
  }
}
