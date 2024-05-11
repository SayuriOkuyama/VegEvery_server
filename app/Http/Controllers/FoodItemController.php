<?php

namespace App\Http\Controllers;

use App\Models\ArticleOfItem;
use App\Models\ArticleOfItemTag;
use App\Models\CommentToItem;
use App\Models\Item;
use App\Models\Report;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\BookshelfArticleOfItem;
use App\Models\Like;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class FoodItemController extends Controller
{
  /**
   * 人気順 にレシピ記事を返す
   */
  public function index(Request $request): JsonResponse
  {
    $page = $request->page;
    if ($page === 'top') {
      $articles = ArticleOfItem::with('user')->orderBy('number_of_likes', 'desc')->take(6)->get();
      return response()->json($articles, 200);
    }
    $articles = ArticleOfItem::with('user')->orderBy('number_of_likes', 'desc')->paginate(20);
    return response()->json($articles, 200);
  }

  /**
   * ワード検索
   */
  public function search(Request $request): JsonResponse
  {
    Log::debug("search");

    Log::debug($request);
    $keyword = $request->search;
    $vegeTag = $request->type;
    Log::debug($keyword);
    Log::debug($vegeTag);

    if (!$keyword || ($keyword == "null")) {
      if ($vegeTag !== "null") {
        $articles = ArticleOfItem::with('user')->where([$vegeTag => true])->orderBy('updated_at', 'desc')->paginate(20);
        return response()->json($articles, 200);
      }
      $articles = ArticleOfItem::with('user')->where(["vegan" => true])->orderBy('updated_at', 'desc')->paginate(20);
      return response()->json($articles, 200);
    }

    $searchedArticles = ArticleOfItem::orWhereRaw("title &@~ ?", [$keyword])->get();
    $searchedItems = Item::orWhereRaw("name &@~ ?", [$keyword])->get();
    $searchedReports = Report::orWhereRaw("text &@~ ?", [$keyword])->get();
    $searchedTags = Tag::orWhereRaw("name &@~ ?", [$keyword])->with("articlesOfItem")->get();

    $articleIds = $searchedArticles->pluck('id')->toArray();
    $articleIdsFromItems = $searchedItems->pluck('article_id')->toArray();
    $articleIdsFromReports = $searchedReports->pluck('article_id')->toArray();
    $articleIdsFromTags = $searchedTags->pluck('id')->toArray();

    $uniqueIds = array_unique(array_merge(
      $articleIds,
      $articleIdsFromItems,
      $articleIdsFromReports,
      $articleIdsFromTags
    ));
    $uniqueSearchedArticles = ArticleOfItem::with('user')->whereIn('id', $uniqueIds)
      ->where([$vegeTag => true])->orderBy('updated_at', 'desc')->paginate(20);
    return response()->json($uniqueSearchedArticles, 200);
  }

  /**
   * 個別記事検索
   */
  public function get(string $id): JsonResponse
  {
    $article = ArticleOfItem::with('user', 'items', 'reports', 'commentsToItem', 'tags')->where('id', $id)->first();

    $commentsWithUserName = [];
    foreach ($article->commentsToItem as $comment) {
      $user = User::find($comment->user_id);
      $commentsWithUserName[] = [
        "id" => $comment->id,
        "user_id" => $comment->user_id,
        "userName" => $user->name,
        "userIcon" => $user->icon_url,
        "userIconPath" => $user->icon_storage_path,
        "text" => $comment->text,
        "likes" => $comment->number_of_likes
      ];
    };

    $likes = Like::where('likeable_id', $id)->where('likeable_type', 'ArticleOfItem')->get();

    $response = [
      "article" => $article,
      "comments" => $commentsWithUserName,
      "likes" => $likes
    ];

    return response()->json($response, 200);
  }

  /**
   * 新規投稿保存
   */
  public function store(Request $request): JsonResponse
  {
    Log::debug($request);
    $path = Storage::putFile('items/thumbnail', $request->file('thumbnail'));
    Log::debug($path);

    $url = "https://static.vegevery.my-raga-bhakti.com/" . $path;
    Log::debug($url);


    $article = ArticleOfItem::create([
      "user_id" => Auth::id(),
      "title" => $request->title,
      "thumbnail_path" => $path,
      "thumbnail_url" => $url,
      "vegan" => $request->vegan === "true" ? true : false,
      "oriental_vegetarian" => $request->oriental_vegetarian === "true" ? true : false,
      "ovo_vegetarian" => $request->ovo_vegetarian === "true" ? true : false,
      "pescatarian" => $request->pescatarian === "true" ? true : false,
      "lacto_vegetarian" => $request->lacto_vegetarian === "true" ? true : false,
      "pollo_vegetarian" => $request->pollo_vegetarian === "true" ? true : false,
      "fruitarian" => $request->fruitarian === "true" ? true : false,
      "other_vegetarian" => $request->other_vegetarian === "true" ? true : false,
    ]);
    Log::debug("ステップ２完了");

    $reportsData = [];
    for ($i = 0; $i < count($request->reports); $i++) {
      $path = "";
      $url = "";
      if (isset($request->reports[$i]["image"])) {
        $path = Storage::putFile('items/report_images', $request->file('reports.' . $i . '.image'));
        $url =  "https://static.vegevery.my-raga-bhakti.com/" . $path;
      }
      $reportsData[] = Report::create([
        "article_of_item_id" => $article->id,
        "order" => $request->reports[$i]["order"],
        "image_path" => $path,
        "image_url" => $url,
        "text" => $request->reports[$i]["text"],
      ]);
    }
    Log::debug("ステップ３完了");

    $itemsData = [];
    foreach ($request->items as $item) {
      $itemsData[] = Item::create([
        "article_of_item_id" => $article->id,
        "name" => $item["name"],
        "where_to_buy" => $item['where_to_buy'],
        "price" => $item['price'],
      ]);
    }
    Log::debug("ステップ４完了");
    Log::debug($itemsData);

    $tagsData = [];
    $articleTagsData = [];
    Log::debug($request->tags);
    foreach ($request->tags as $tag) {
      Log::debug($tag["name"]);
      if ($tag["name"] !== "") {
        $tagData = Tag::firstOrCreate(['name' => $tag["name"]]);
        $tagsData[] = $tagData;

        $articleTagsData[] = ArticleOfItemTag::create([
          'article_of_item_id' => $article->id,
          'tag_id' => $tagData->id
        ]);
      }
    }
    Log::debug("ステップ5完了");

    $response = [
      "article" => $article,
      "reportsData" => $reportsData,
      "itemsData" => $itemsData,
      "tagsData" => $tagsData,
      "articleTagsData" => $articleTagsData
    ];

    return response()->json($response);
  }

  /**
   * コメント投稿
   */
  public function commentStore(Request $request, string $id): JsonResponse
  {
    Log::debug($request);
    Log::debug($id);

    $user = User::find($request->user_id);
    $commentData = CommentToItem::create([
      "article_of_item_id" => $id,
      "user_id" => $user->id,
      "text" => $request->text
    ]);
    Log::debug($commentData);

    $commentWithUserName = [
      "id" => $commentData->id,
      "userName" => $user->name,
      "userIcon" => $user->icon_url,
      "text" => $commentData->text,
      "likes" => 0
    ];
    Log::debug($commentWithUserName);

    return response()->json($commentWithUserName);
  }

  /**
   * 投稿記事更新
   */
  public function update(Request $request, string $id): JsonResponse
  {
    Log::debug($request);
    $article = ArticleOfItem::with('user', "items")->where('id', $id)->first();

    $path = !$request->thumbnail_path ?
      Storage::putFile('items/thumbnail', $request->file('thumbnail_newFile')) :
      $request->thumbnail_path;
    $url = !$request->thumbnail_path ?
      "https://static.vegevery.my-raga-bhakti.com/" . $path :
      $request->thumbnail_url;

    // if (!$request->thumbnail_path) {
    //   $path = Storage::putFile('items/thumbnail', $request->file('thumbnail_newFile'));
    //   $url = "https://static.vegevery.my-raga-bhakti.com/" . $path;
    // } else {
    //   $path = $request->thumbnail_path;
    //   $url = $request->thumbnail_url;
    // }

    $article->title = $request->title;
    $article->thumbnail_path = $path;
    $article->thumbnail_url = $url;
    $article->vegan = $request->vegan === "true" ? true : false;
    $article->oriental_vegetarian = $request->oriental_vegetarian === "true" ? true : false;
    $article->ovo_vegetarian = $request->ovo_vegetarian === "true" ? true : false;
    $article->pescatarian = $request->pescatarian === "true" ? true : false;
    $article->lacto_vegetarian = $request->lacto_vegetarian === "true" ? true : false;
    $article->pollo_vegetarian = $request->pollo_vegetarian === "true" ? true : false;
    $article->fruitarian = $request->fruitarian === "true" ? true : false;
    $article->other_vegetarian = $request->other_vegetarian === "true" ? true : false;
    $article->push();
    Log::debug("第一ステップ完了");

    $oldReports = Report::where('article_of_item_id', $article->id)->get();
    $reportsData = [];

    foreach ($oldReports as $oldReport) {
      $oldReport->delete();
    }

    $reportsData = [];
    for ($i = 0; $i < count($request->reports); $i++) {
      $path = "";
      $url = "";
      if (isset($request->reports[$i]["file"]) && $request->reports[$i]["file"] != "undefined") {
        $path = Storage::putFile('items/report_images', $request->file('reports.' . $i . '.file'));
        $url =  "https://static.vegevery.my-raga-bhakti.com/" . $path;
      } elseif (
        !isset(
          $request->reports[$i]["file"]
        )
        && (
          !isset($request->reports[$i]["url"])
          || $request->reports[$i]["url"] === ""
        )
      ) {
        $path = "";
        $url = "";
      } else {
        $path = $request->reports[$i]["path"];
        $url = $request->reports[$i]["url"];
      }
      $reportsData[] = Report::create([
        "article_of_item_id" => $article->id,
        "order" => $request->reports[$i]["order"],
        "image_path" => $path,
        "image_url" => $url,
        "text" => $request->reports[$i]["text"],
      ]);
    }
    Log::debug("ステップ2完了");

    $tagsData = [];
    $articleTagsData = [];
    $articleTags = ArticleOfItemTag::where(['article_of_item_id' => $article->id])->get();

    if ($articleTags != null) {
      foreach ($articleTags as $articleTag) {
        $articleTag->delete();
      }
    }
    Log::debug("ステップ3完了");

    if ($request->tags) {
      foreach ($request->tags as $newTag) {
        if ($newTag["name"] !== null) {
          $tag_data = Tag::firstOrCreate(['name' => $newTag["name"]]);
          $tagsData[] = $tag_data;

          $articleTagsData[] = ArticleOfItemTag::firstOrCreate([
            'article_of_item_id' => $article->id,
            'tag_id' => $tag_data->id
          ]);
        }
      }
    }

    $article->push();
    Log::debug("ステップ4完了");

    $oldItems = $article->items;

    foreach ($oldItems as $oldItem) {
      $oldItem->delete();
    }

    $itemsData = [];
    foreach ($request->items as $item) {
      $itemsData[] = Item::create([
        "article_of_item_id" => $article->id,
        "name" => $item["name"],
        "where_to_buy" => $item['where_to_buy'],
        "price" => $item['price'],
      ]);
    }
    Log::debug("ステップ5完了");

    $response = [
      "article" => $article,
      "reportsData" => $reportsData,
      "itemsData" => $itemsData,
      "tagsData" => $tagsData,
      "articleTagsData" => $articleTagsData
    ];

    return response()->json($response);
  }

  /**
   * Remove the specified resource from storage.
   */
  public function delete(string $id): JsonResponse
  {
    Log::debug($id);
    Report::where("article_of_item_id", $id)->delete();
    Log::debug("1完了");
    Item::where("article_of_item_id", $id)->delete();
    Log::debug("2完了");
    ArticleOfItemTag::where("article_of_item_id", $id)->delete();
    Log::debug("3完了");
    CommentToItem::where("article_of_item_id", $id)->delete();
    Log::debug("4完了");
    BookshelfArticleOfItem::where("article_of_item_id", $id)->delete();
    Log::debug("5完了");
    ArticleOfItem::find($id)->delete();
    return response()->json("削除しました");
  }

  /**
   * コメント削除
   */
  public function commentDelete(Request $request): JsonResponse
  {
    Log::debug($request);
    CommentToItem::find($request->id)->delete();
    return response()->json("削除しました");
  }
}
