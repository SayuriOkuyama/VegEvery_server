<?php

namespace App\Http\Controllers;

use App\Enums\OAuthProviderEnum;
use App\Models\ArticleOfItem;
use App\Models\ArticleOfRecipe;
use App\Models\Bookshelf;
use App\Models\RecipeStep;
use App\Models\Report;
use App\Models\Review;
use App\Models\SocialAccount;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class AuthController extends Controller
{
  public function index(Request $request)
  {
    Log::debug($request->user());
    return $request->user();
  }

  public function getUser(string $id)
  {
    Log::debug("getUser");
    $user = User::find($id);

    return response()->json($user);
  }

  /**
   * ユーザー個人のレシピ記事を返す
   */
  public function getUserArticles(string $id, Request $request)
  {
    Log::debug("getUserArticles");
    Log::debug($request);
    if ($request->articleType === "recipes") {
      $articles = ArticleOfRecipe::with('user')->where("user_id", $id)->orderBy('updated_at', 'desc')->paginate(20);
      return response()->json($articles, 200);
    } else {
      $articles = ArticleOfItem::with('user')->where("user_id", $id)->orderBy('updated_at', 'desc')->paginate(20);
      return response()->json($articles, 200);
    }
  }

  public function getArticles()
  {
    $user = Auth::user();

    $recipes = ArticleOfRecipe::where("user_id", $user->id)->orderBy('updated_at', 'desc')->paginate(20);

    $items = ArticleOfItem::where("user_id", $user->id)->orderBy('updated_at', 'desc')->paginate(20);

    $response = [
      "recipes" => $recipes,
      "items" => $items
    ];

    return response()->json($response);
  }

  public function register(Request $request)
  {
    Log::debug("Auth-register");
    Log::debug($request);

    $path = "";
    $url = "";
    if ($request->iconFile) {
      $path = Storage::putFile('recipes/thumbnail', $request->file('iconFile'));
      $url = "https://static.vegevery.my-raga-bhakti.com/" . $path;
    } else if ($request->iconUrl === 'https://static.vegevery.my-raga-bhakti.com/user/icon_image/user_icon.png') {
      $path = "user/icon_image/user_icon.png";
      $url = $request->iconUrl;
    } else {
      // provider 情報から修正がない場合
      $path = "";
      $url = $request->iconUrl;
    }

    if ($request->provider) {
      // ランダムな文字列を生成し、テーブルに存在する場合は作り直す
      do {
        $randomString = Str::random(15);
      } while (User::where('account_id', $randomString)->exists());

      $user = User::create([
        "account_id" => $randomString,
        'name' => $request->name,
        'vegetarian_type' => $request->vegeType,
        'icon_url' => $url,
        'icon_storage_path' => $path
      ]);

      $social = SocialAccount::create([
        "user_id" => $user->id,
        "provider" => $request->provider,
        "provider_id" => $request->providerId
      ]);
      Log::debug($social);
    } else {
      $user = User::create([
        "account_id" => $request->account_id,
        'name' => $request->name,
        'password' => $request->password,
        'secret_question' => $request->secretQuestion,
        'answer_to_secret_question' => $request->secretAnswer,
        'vegetarian_type' => $request->vegeType,
        'icon_url' => $url,
        'icon_storage_path' => $path
      ]);
    }

    Log::debug($user);
    // Laravel Sanctumのトークンを発行
    $token = $user->createToken('sanctum_token')->plainTextToken;

    return response()->json(['token' => $token, "user" => $user], 200);
  }


  /**
   * アカウント ID が使用可能か確認
   */
  public function checkAccountId(Request $request)
  {
    Log::debug($request);
    $result = User::where('account_id', $request->id)->first();
    Log::debug($result);

    if ($result) {
      return response()->json(['result' => false]);
    }
    return response()->json(['result' => true]);
  }


  public function login(Request $request)
  {
    Log::debug("Auth-login");
    Log::debug($request);

    // social ログインの場合
    if ($request->provider) {
      $social_account = SocialAccount::where([
        ['provider_id', '=', $request->providerId],
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
          'errors' => [
            'login' => ['IDかパスワードが間違っています']
          ]
        ], 422);
      }

      $token = $user->createToken('sanctum_token')->plainTextToken;
      Log::debug(['token' => $token, 'user' => $user]);
      return response()->json(['token' => $token, 'user' => $user], 200);
    }
  }

  public function logout(Request $request)
  {
    // 現在のアクセストークンを削除して特定のセッションをログアウト
    $request->user()->currentAccessToken()->delete();

    return response()->json(['message' => 'ログアウトしました。'], 200);
  }

  public function update(string $id, Request $request)
  {
    Log::debug("Auth-update");
    Log::debug($request);

    $user = User::find($id);

    if ($request->icon_file) {
      $path = Storage::putFile('user', $request->file('iconFile'));
      $user->icon_storage_path = $path;
      $user->icon_url = "https://static.vegevery.my-raga-bhakti.com/" . $path;
    } else if (!$request->icon_url) {
      $user->icon_storage_path = "user/icon_image/user_icon.png";
      $user->icon_url = 'https://static.vegevery.my-raga-bhakti.com/user/icon_image/user_icon.png';
    }

    $vegetarian_type = [
      "ヴィーガン" => 'vegan',
      "オリエンタル・ベジタリアン" => 'oriental_vegetarian',
      "オボ・ベジタリアン" => 'ovo_vegetarian',
      "ペスカタリアン" => 'pescatarian',
      "ラクト・ベジタリアン" => 'lacto_vegetarian',
      "ポーヨ・ベジタリアン" => 'pollo_vegetarian',
      "フルータリアン" => 'fruitarian',
      "その他のベジタリアン" => 'other_vegetarian',
    ];

    $user->name = $request->name;
    $user->vegetarian_type = $vegetarian_type[$request->vegetarian_type];
    $user->introduction = $request->introduction;

    $user->save();

    return response()->json($user);
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

  public function passwordReset(string $id, Request $request)
  {
    Log::debug("passwordReset");
    Log::debug($request);

    $user = User::find($id);

    $user->password = $request->password;

    if ($request->secretQuestion) {
      $user->secret_question = $request->secretQuestion;
      $user->answer_to_secret_question = $request->secretAnswer;
    }
    $user->save();

    $response = [
      "message" => "パスワードを更新しました。",
      "user" => $user
    ];

    return response()->json($response);
  }

  public function searchUser(Request $request)
  {
    Log::debug("searchUser");
    Log::debug($request->id);

    $user = User::where("account_id", $request->id)->first();

    if ($user) {
      $response = [
        "id" => $user->id,
        "question" => $user->secret_question,
        "answer" => $user->answer_to_secret_question,
      ];
    } else {
      $response = [
        "message" => "failed"
      ];
    }

    return response()->json($response);
  }

  public function deleteAccount(string $id)
  {
    Log::debug("deleteAccount");

    $user = User::find($id);

    // 画像削除が必要なモデル
    // ArticleOfRecipe,RecipeStep,ArticleOfItem,Report,Review,User

    if ($user->icon_storage_path !== "user/icon_image/user_icon.png") {
      Storage::delete($user->icon_storage_path);
    }

    $ArticlesOfRecipe = ArticleOfRecipe::where("user_id", $id)->get();

    foreach ($ArticlesOfRecipe as $ArticleOfRecipe) {
      Storage::delete($ArticleOfRecipe->thumbnail_path);

      $steps = RecipeStep::where("article_of_recipe_id", $ArticleOfRecipe->id)->get();
      foreach ($steps as $step) {
        Storage::delete($step->image_path);
      }
    }

    $ArticlesOfItem = ArticleOfItem::where("user_id", $id)->get();

    foreach ($ArticlesOfItem as $ArticleOfItem) {
      Storage::delete($ArticleOfItem->thumbnail_path);

      $reports = Report::where("article_of_item_id", $ArticleOfItem->id)->get();
      foreach ($reports as $report) {
        Storage::delete($report->image_path);
      }
    }

    $reviews = Review::where("user_id", $id)->get();

    foreach ($reviews as $review) {
      Storage::delete($review->thumbnail_path);
    }

    // 画像削除が不要なモデル
    // Material,Item,Menu,Bookshelf(今後サムネイルを設定するなら必要)、
    // CommentToRecipe,CommentToItem,SocialAccount,
    // ArticleOfRecipeTag,ArticleOfItemTag,BookshelfArticleOfRecipe,BookshelfArticleOfItem

    $user->delete();

    return response()->json("削除しました。");
  }
}
