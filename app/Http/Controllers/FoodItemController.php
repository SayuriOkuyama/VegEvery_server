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

class FoodItemController extends Controller
{
  /**
   * 人気順 にレシピ記事を返す
   */
  public function index(Request $request)
  {
    $page = $request->page;
    if ($page === 'top') {
      $articles = ArticleOfItem::with('user')->orderBy('number_of_likes', 'desc')->take(6)->get();
      return response()->json($articles, 200);
    } else {
      $articles = ArticleOfItem::with('user')->orderBy('number_of_likes', 'desc')->paginate(20);
      return response()->json($articles, 200);
    }
  }

  /**
   * ワード検索
   */
  public function search(Request $request)
  {
    Log::debug("search");

    Log::debug($request);
    $keyword = $request->search;
    $vegeTag = $request->type;
    Log::debug($keyword);
    Log::debug($vegeTag);

    if (!$keyword || ($keyword == "null")) {
      if ($vegeTag !== "null" || !$vegeTag) {
        $articles = ArticleOfItem::with('user')->where([$vegeTag => true])->orderBy('updated_at', 'desc')->paginate(20);
        return response()->json($articles, 200);
      } else {
        $articles = ArticleOfItem::with('user')->where(["vegan" => true])->orderBy('updated_at', 'desc')->paginate(20);
        return response()->json($articles, 200);
      }
    } else {
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
  }

  /**
   * 個別記事検索
   */
  public function get(string $id)
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
        "text" => $comment->text,
        "likes" => $comment->number_of_likes
      ];
    };

    $response = [
      "article" => $article,
      "comments" => $commentsWithUserName
    ];

    return response()->json($response, 200);
  }

  /**
   * 新規投稿保存
   */
  public function store(Request $request)
  {
    Log::debug($request);

    $article = ArticleOfItem::create([
      "user_id" => $request->user_id,
      "title" => $request->title,
      "thumbnail_path" => $request->thumbnail["thumbnail_path"],
      "thumbnail_url" => $request->thumbnail["thumbnail_url"],
      "vegan" => $request->vege_type['vegan'],
      "oriental_vegetarian" => $request->vege_type['oriental_vegetarian'],
      "ovo_vegetarian" => $request->vege_type['ovo_vegetarian'],
      "pescatarian" => $request->vege_type['pescatarian'],
      "lacto_vegetarian" => $request->vege_type['lacto_vegetarian'],
      "pollo_vegetarian" => $request->vege_type['pollo_vegetarian'],
      "fruitarian" => $request->vege_type['fruitarian'],
      "other_vegetarian" => $request->vege_type['other_vegetarian'],
    ]);

    $reportsData = [];
    for ($i = 0; $i < count($request->reports["reports_order_text"]); $i++) {
      if (isset($request->reports["reportImages"][$i]["image_path"])) {
        $reportsData[] = Report::create([
          "article_of_item_id" => $article->id,
          "order" => $request->reports["reports_order_text"][$i]["order"],
          "image_path" => $request->reports["reportImages"][$i]["image_path"],
          "image_url" => $request->reports["reportImages"][$i]["image_url"],
          "text" => $request->reports["reports_order_text"][$i]["text"],
        ]);
      } else {
        $reportsData[] = Report::create([
          "article_of_item_id" => $article->id,
          "order" => $request->reports["reports_order_text"][$i]["order"],
          "image_path" => "",
          "image_url" => "",
          "text" => $request->reports["reports_order_text"][$i]["text"],
        ]);
      }
    }

    $itemsData = [];
    for ($i = 0; $i < count($request->items); $i++) {
      $itemsData[] = Item::create([
        "article_of_item_id" => $article->id,
        "name" => $request->items[$i]["name"],
        "where_to_buy" => $request->items[$i]['place'],
        "price" => $request->items[$i]['price'],
      ]);
    }
    Log::debug($itemsData);

    $tagsData = [];
    $articleTagsData = [];
    if ($request->tags) {
      foreach ($request->tags as $tag) {
        if ($tag !== null) {
          $tag_data = Tag::firstOrCreate(['name' => $tag["tag"]]);
          $tagsData[] = $tag_data;

          $articleTagsData[] = ArticleOfItemTag::create([
            'article_of_item_id' => $article->id,
            'tag_id' => $tag_data->id
          ]);
        }
      }
    }

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
  public function commentStore(Request $request, string $id)
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
  public function update(Request $request, string $id)
  {
    Log::debug($request);
    $article = ArticleOfItem::with('user', "items")->where('id', $id)->first();

    $article->title = $request->title;
    $article->thumbnail_path = $request->thumbnail["thumbnail_path"];
    $article->thumbnail_url = $request->thumbnail["thumbnail_url"];
    $article->vegan = $request->vege_type["vegan"];
    $article->oriental_vegetarian = $request->vege_type["oriental_vegetarian"];
    $article->ovo_vegetarian = $request->vege_type["ovo_vegetarian"];
    $article->pescatarian = $request->vege_type["pescatarian"];
    $article->lacto_vegetarian = $request->vege_type["lacto_vegetarian"];
    $article->pollo_vegetarian = $request->vege_type["pollo_vegetarian"];
    $article->fruitarian = $request->vege_type["fruitarian"];
    $article->other_vegetarian = $request->vege_type["other_vegetarian"];

    $oldReports = Report::where('article_of_item_id', $article->id)->get();
    $newReports = $request->reports;
    $reportsData = [];

    foreach ($oldReports as $oldReport) {
      $oldReport->delete();
    }

    for ($i = 0; $i < count($request->reports["report_order_text"]); $i++) {
      if (isset($newReports["reportImages"][$i]["image_path"])) {
        $reportsData[] = Report::create([
          "article_of_item_id" => $article->id,
          "order" => $newReports["report_order_text"][$i]["order"],
          "image_path" => $newReports["reportImages"][$i]["image_path"],
          "image_url" => $newReports["reportImages"][$i]["image_url"],
          "text" => $newReports["report_order_text"][$i]["text"],
        ]);
      } else {
        $reportsData[] = Report::create([
          "article_of_item_id" => $article->id,
          "order" => $newReports["report_order_text"][$i]["order"],
          "image_path" => "",
          "image_url" => "",
          "text" => $newReports["report_order_text"][$i]["text"],
        ]);
      }
    }

    $tagsData = [];
    $articleTagsData = [];
    $article_tags = ArticleOfItemTag::where(['article_of_item_id' => $article->id])->get();

    if ($article_tags != null) {
      foreach ($article_tags as $article_tag) {
        $article_tag->delete();
      }
    }

    if ($request->tags) {
      foreach ($request->tags as $newTag) {
        if ($newTag !== null) {
          $tag_data = Tag::firstOrCreate(['name' => $newTag]);
          $tagsData[] = $tag_data;

          $articleTagsData[] = ArticleOfItemTag::firstOrCreate([
            'article_of_item_id' => $article->id,
            'tag_id' => $tag_data->id
          ]);
        }
      }
    }

    $article->push();

    $newItems = $request->items;
    $oldItems = $article->items;
    Log::debug("newItems↓");
    Log::debug($newItems);
    Log::debug("oldItems↓");
    Log::debug($oldItems);
    $maxItemsNum = max(count($newItems), count($oldItems));
    $itemsData = [];
    for ($i = 0; $i < $maxItemsNum; $i++) {
      if (
        isset($newItems[$i]) && isset($oldItems[$i]) && isset($newItems[$i]["id"])
        && $newItems[$i]["id"] === $oldItems[$i]["id"]
      ) {
        $oldItems[$i]->name = $newItems[$i]["name"];
        $oldItems[$i]->where_to_buy = $newItems[$i]["where_to_buy"];
        $oldItems[$i]->price = $newItems[$i]["price"];
      } else {
        Log::debug("id違う");
        if (isset($newItems[$i])) {
          Log::debug("新しい材料あり");
          $itemsData[] = Item::create([
            "article_of_item_id" => $article->id,
            "name" => $newItems[$i]["name"],
            "where_to_buy" => $newItems[$i]['where_to_buy'],
            "price" => $newItems[$i]['price'],
          ]);
        }
        if (isset($oldItems[$i])) {
          Log::debug("古い材料削除");
          Log::debug($oldItems[$i]);
          $oldItems[$i]->delete();
        }
      }
    }
    Log::debug("itemsData↓");
    Log::debug($itemsData);

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
  public function delete(string $id)
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
  public function commentDelete(Request $request)
  {
    Log::debug($request);
    CommentToItem::find($request->id)->delete();
    return response()->json("削除しました");
  }
}
