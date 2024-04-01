<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Http\RedirectResponse;

class LoginController extends Controller
{
  /**
   * OAuthプロバイダへリダイレクトする
   *
   * @return RedirectResponse
   */
  public function redirectToGoogle(): RedirectResponse
  {
    return Socialite::driver('google')->redirect();
  }

  /**
   * OAuthプロバイダからのコールバックを処理する
   *
   * @return RedirectResponse
   */
  public function handleGoogleCallback(): RedirectResponse
  {
    // 認証されたユーザーの情報を取得
    $googleUser = Socialite::driver('google')->user();

    $user = User::updateOrCreate([
      'google_id' => $googleUser->getId(),
    ], [
      'name' => $googleUser->getName(),
      'email' => $googleUser->getEmail(),
      'avatar' => $googleUser->getAvatar(),
    ]);

    // Auth ファサードの login メソッドにユーザーを渡すとログインとなる
    Auth::login($user);

    return redirect()->route('user.index');
  }

  /**
   * ログアウトする
   *
   * @return RedirectResponse
   */
  public function logout(): RedirectResponse
  {
    Auth::logout();
    return redirect()->route('welcome');
  }
}
