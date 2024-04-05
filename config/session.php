<?php

use Illuminate\Support\Str;

return [

  /*
    |--------------------------------------------------------------------------
    | Default Session Driver
    |--------------------------------------------------------------------------
    |
    | このオプションは、リクエストで使用されるデフォルトのセッション「ドライバー」を制御します。
    | デフォルトでは、軽量のネイティブ ドライバーを使用しますが、
    | ここで提供されている他の優れたドライバーを指定することもできます。
    | サポートされている: 「ファイル」、「クッキー」、「データベース」、「
    | apc」、「memcached」、「redis」、「dynamodb」、「array」
    |
    */

  'driver' => env('SESSION_DRIVER', 'file'),

  /*
    |--------------------------------------------------------------------------
    | Session Lifetime
    |--------------------------------------------------------------------------
    |
    | Here you may specify the number of minutes that you wish the session
    | to be allowed to remain idle before it expires. If you want them
    | to immediately expire on the browser closing, set that option.
    |
    */

  'lifetime' => env('SESSION_LIFETIME', 120),

  'expire_on_close' => false,

  /*
    |--------------------------------------------------------------------------
    | Session Encryption
    |--------------------------------------------------------------------------
    |
    | This option allows you to easily specify that all of your session data
    | should be encrypted before it is stored. All encryption will be run
    | automatically by Laravel and you can use the Session like normal.
    |
    */

  'encrypt' => false,

  /*
    |--------------------------------------------------------------------------
    | Session File Location
    |--------------------------------------------------------------------------
    |
    | When using the native session driver, we need a location where session
    | files may be stored. A default has been set for you but a different
    | location may be specified. This is only needed for file sessions.
    |
    */

  'files' => storage_path('framework/sessions'),

  /*
    |--------------------------------------------------------------------------
    | Session Database Connection
    |--------------------------------------------------------------------------
    |
    | When using the "database" or "redis" session drivers, you may specify a
    | connection that should be used to manage these sessions. This should
    | correspond to a connection in your database configuration options.
    |
    */

  'connection' => env('SESSION_CONNECTION'),

  /*
    |--------------------------------------------------------------------------
    | Session Database Table
    |--------------------------------------------------------------------------
    |
    | When using the "database" session driver, you may specify the table we
    | should use to manage the sessions. Of course, a sensible default is
    | provided for you; however, you are free to change this as needed.
    |
    */

  'table' => 'sessions',

  /*
    |--------------------------------------------------------------------------
    | Session Cache Store
    |--------------------------------------------------------------------------
    |
    | While using one of the framework's cache driven session backends you may
    | list a cache store that should be used for these sessions. This value
    | must match with one of the application's configured cache "stores".
    |
    | Affects: "apc", "dynamodb", "memcached", "redis"
    |
    */

  'store' => env('SESSION_STORE'),

  /*
    |--------------------------------------------------------------------------
    | Session Sweeping Lottery
    |--------------------------------------------------------------------------
    |
    | このオプションは、リクエストで使用されるデフォルトのセッション「ドライバー」を制御します。
    | デフォルトでは、軽量のネイティブドライバーを使用しますが、
    | 古いセッションはストレージから削除されます。
    | 特定のリクエストでそれが発生する可能性は次のとおりです。
    | デフォルトでは、確率は 100 分の 2 です。
    |
    */

  'lottery' => [2, 100],

  /*
    |--------------------------------------------------------------------------
    | Session Cookie Name
    |--------------------------------------------------------------------------
    |
    | ここで、セッション インスタンスを ID で識別するために使用される Cookie の名前を変更できます。
    | ここで指定した名前は、すべてのドライバーのフレームワークによって
    | 新しいセッション Cookie が作成されるたびに使用されます。
    |
    */

  'cookie' => env(
    'SESSION_COOKIE',
    Str::slug(env('APP_NAME', 'laravel'), '_') . '_session'
  ),

  /*
    |--------------------------------------------------------------------------
    | Session Cookie Path
    |--------------------------------------------------------------------------
    |
    | セッション Cookie のパスによって、Cookie が使用可能であると見なされるパスが決まります。
    | 通常、これはアプリケーションのルート パスになりますが、必要に応じて自由に変更できます。
    |
    */

  'path' => '/',

  /*
    |--------------------------------------------------------------------------
    | Session Cookie Domain
    |--------------------------------------------------------------------------
    |
    | ここで、アプリケーション内のセッションを識別するために使用される
    | Cookie のドメインを変更できます。
    | これにより、アプリケーション内で Cookie を使用できるドメインが決まります。
    | 適切なデフォルトが設定されています。
    |
    */

  'domain' => env('SESSION_DOMAIN'),

  /*
    |--------------------------------------------------------------------------
    | HTTPS Only Cookies
    |--------------------------------------------------------------------------
    |
    | このオプションを true に設定すると、ブラウザーに HTTPS 接続がある場合にのみ、
    | セッション Cookie がサーバーに送り返されます。
    | これにより、安全に送信できない場合に Cookie が送信されなくなります。
    |
    */

  'secure' => env('SESSION_SECURE_COOKIE'),

  /*
    |--------------------------------------------------------------------------
    | HTTP Access Only
    |--------------------------------------------------------------------------
    |
    | この値を true に設定すると、JavaScript が Cookie の値にアクセスできなくなり、
    | Cookie には HTTP プロトコル経由でのみアクセスできるようになります。
    | 必要に応じて、このオプションを自由に変更できます。
    |
    */

  'http_only' => true,

  /*
    |--------------------------------------------------------------------------
    | Same-Site Cookies
    |--------------------------------------------------------------------------
    |
    | This option determines how your cookies behave when cross-site requests
    | take place, and can be used to mitigate CSRF attacks. By default, we
    | will set this value to "lax" since this is a secure default value.
    |
    | Supported: "lax", "strict", "none", null
    |
    */

  'same_site' => 'lax',

  /*
    |--------------------------------------------------------------------------
    | Partitioned Cookies
    |--------------------------------------------------------------------------
    |
    | この値を true に設定すると、Cookie がクロスサイト コンテキストのトップレベル サイトに関連付けられます。
    | 「セキュア」フラグが設定され、Same-Site 属性が「none」に設定されている場合、
    | ブラウザーはパーティション化された Cookie を受け入れます。
    */

  'partitioned' => false,

];
