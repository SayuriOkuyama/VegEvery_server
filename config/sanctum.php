<?php

return [

  /*
    |--------------------------------------------------------------------------
    | Stateful Domains
    |--------------------------------------------------------------------------
    |
    | Requests from the following domains / hosts will receive stateful API
    | authentication cookies. Typically, these should include your local
    | and production domains which access your API via a frontend SPA.
    |
    */

  'stateful' => explode(',', env('SANCTUM_STATEFUL_DOMAINS', sprintf(
    '%s%s%s',
    'localhost,localhost:8000,localhost:3001',
    '192.168.3.21,192.168.3.21:3001,192.168.3.21:8000,127.0.0.1,127.0.0.1:8000,127.0.0.1:3001,::1',
    'SANCTUM_STATEFUL_DOMAINS',
    '192.168.3.21:3001',
    env('APP_URL') ? ',' . parse_url(env('APP_URL'), PHP_URL_HOST) : '',
    env('FRONTEND_URL') ? ',' . parse_url(env('FRONTEND_URL'), PHP_URL_HOST) : ''
  ))),

  /*
    |--------------------------------------------------------------------------
    | Sanctum Guards
    |--------------------------------------------------------------------------
    |
    | This array contains the authentication guards that will be checked when
    | Sanctum is trying to authenticate a request. If none of these guards
    | are able to authenticate the request, Sanctum will use the bearer
    | token that's present on an incoming request for authentication.
    |
    */

  'guard' => ['web'],

  /*
    |--------------------------------------------------------------------------
    | 有効期限（分）
    |--------------------------------------------------------------------------
    | この値は、発行されたトークンが期限切れとみなされるまでの分数を制御します。
    | これにより、トークンの「expires_at」属性に設定された値がオーバーライドされますが
    | ファーストパーティのセッションは影響を受けません。
    */

  'expiration' => 120,

  /*
    |--------------------------------------------------------------------------
    | Sanctum Middleware
    |--------------------------------------------------------------------------
    |
    | When authenticating your first-party SPA with Sanctum you may need to
    | customize some of the middleware Sanctum uses while processing the
    | request. You may change the middleware listed below as required.
    |
    */

  'middleware' => [
    'verify_csrf_token' => App\Http\Middleware\VerifyCsrfToken::class,
    'encrypt_cookies' => App\Http\Middleware\EncryptCookies::class,
  ],

];
