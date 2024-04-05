<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
  /**
   * The application's global HTTP middleware stack.
   *
   * These middleware are run during every request to your application.
   *
   * @var array<int, class-string|string>
   */
  protected $middleware = [
    // \App\Http\Middleware\TrustHosts::class,
    \App\Http\Middleware\TrustProxies::class,
    \Illuminate\Http\Middleware\HandleCors::class,
    \App\Http\Middleware\PreventRequestsDuringMaintenance::class,
    \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
    \App\Http\Middleware\TrimStrings::class,
    \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
  ];

  /**
   * The application's route middleware groups.
   *
   * @var array<string, array<int, class-string|string>>
   */
  protected $middlewareGroups = [
    'web' => [
      \App\Http\Middleware\EncryptCookies::class,
      \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
      \Illuminate\Session\Middleware\StartSession::class,
      // セッションからエラーメッセージを取得し、ビューに共有するためのミドルウェア
      \Illuminate\View\Middleware\ShareErrorsFromSession::class,
      // CSRF トークンの検証を行うためのミドルウェア
      // フォームリクエストが信頼できるものであることを確認
      \App\Http\Middleware\VerifyCsrfToken::class,
      // ルートモデルバインディングを処理するためのミドルウェア
      \Illuminate\Routing\Middleware\SubstituteBindings::class,
    ],

    'api' => [
      // フロントエンドリクエストがセッション情報を持っていることを確認するためのミドルウェア
      \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
      // API ルートへのリクエストのスロットルを設定するためのミドルウェア
      // APIへのリクエストが過剰になることを防ぎ、サーバーの負荷を軽減
      \Illuminate\Routing\Middleware\ThrottleRequests::class . ':api',
      \Illuminate\Routing\Middleware\SubstituteBindings::class,
    ],

    'session' => [
      // クッキーの内容を暗号化するためのミドルウェア
      \App\Http\Middleware\EncryptCookies::class,
      // クッキーをレスポンスに追加するためのミドルウェア
      \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
      // セッションを開始し、セッションIDをクライアントに送信するためのミドルウェア
      \Illuminate\Session\Middleware\StartSession::class,
    ],
  ];

  /**
   * The application's middleware aliases.
   *
   * Aliases may be used instead of class names to conveniently assign middleware to routes and groups.
   *
   * @var array<string, class-string|string>
   */
  protected $middlewareAliases = [
    'auth' => \App\Http\Middleware\Authenticate::class,
    'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
    'auth.session' => \Illuminate\Session\Middleware\AuthenticateSession::class,
    'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
    'can' => \Illuminate\Auth\Middleware\Authorize::class,
    'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
    'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
    'precognitive' => \Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests::class,
    'signed' => \App\Http\Middleware\ValidateSignature::class,
    'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
    'verified' => \App\Http\Middleware\EnsureEmailIsVerified::class,
  ];
}
