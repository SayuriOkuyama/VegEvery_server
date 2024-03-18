<?php

namespace App\Http\Controllers;

use App\Models\ArticleOfItem;
use App\Models\ArticleOfItemTag;
use App\Models\Item;
use App\Models\Report;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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

  public function search(Request $request)
  {
    $vegeTag = $request->vegeTag;
    $articles = ArticleOfItem::with('user')->where([$vegeTag => 1])->orderBy('number_of_likes', 'desc')->paginate(20);
    return response()->json($articles, 200);
  }

  public function get(string $id)
  {
    $article = ArticleOfItem::with('user', 'items', 'reports', 'commentsToItem', 'tags')->where('id', $id)->first();

    $commentsWithUserName = [];
    foreach ($article->commentsToItem as $comment) {
      $user = User::find($comment->user_id);
      $commentsWithUserName[] = [
        "id" => $comment->id,
        "userName" => $user->name,
        "userIcon" => $user->icon,
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
   * Store a newly created resource in storage.
   */
  public function store(Request $request)
  {
    Log::debug($request);
    // $vege_types = [];
    // foreach ($request->values['vegeTags'] as $type => $value) {

    //   if ($value) {
    //     $vege_types[$type] = 1;
    //   } else {
    //     $vege_types[$type] = 0;
    //   }
    // }

    $article = ArticleOfItem::create([
      "user_id" => 1,
      "title" => $request->values["title"],
      "thumbnail_path" => $request->values["thumbnail"]["thumbnail_path"],
      "thumbnail_url" => $request->values["thumbnail"]["thumbnail_url"],
      "vegan" => $request->values["vegeTags"]['vegan'],
      "oriental_vegetarian" => $request->values["vegeTags"]['oriental_vegetarian'],
      "ovo_vegetarian" => $request->values["vegeTags"]['ovo_vegetarian'],
      "pescatarian" => $request->values["vegeTags"]['pescatarian'],
      "lacto_vegetarian" => $request->values["vegeTags"]['lacto_vegetarian'],
      "pollo_vegetarian" => $request->values["vegeTags"]['pollo_vegetarian'],
      "fruitarian" => $request->values["vegeTags"]['fruitarian'],
      "other_vegetarian" => $request->values["vegeTags"]['other_vegetarian'],
    ]);
    Log::debug("第１ステップ完了");

    $reportsData = [];
    foreach ($request->values["reports"]["reports_order_text"] as $index => $report) {
      if (isset($request->values["reports"]["reportImages"][$index]["image_path"])) {
        $reportsData[] = Report::create([
          "article_of_item_id" => $article->id,
          "order" => $report["order"],
          "text" => $report["text"],
          "image_path" => $request->values["reports"]["reportImages"][$index]["image_path"],
          "image_url" => $request->values["reports"]["reportImages"][$index]["image_url"]
        ]);
      } else {
        $reportsData[] = Report::create([
          "article_of_item_id" => $article->id,
          "order" => $report["order"],
          "text" => $report["text"],
          "image_path" => "",
          "image_url" => ""
        ]);
      }
    };
    Log::debug("第２ステップ完了");

    $itemsData = [];
    for ($i = 0; $i < count($request->values["items"]); $i++) {
      $itemsData[] = Item::create([
        "article_of_item_id" => $article->id,
        "name" => $request->values["items"][$i]["name"],
        "where_to_buy" => $request->values["items"][$i]['place'],
        "price" => $request->values["items"][$i]['price'],
      ]);
    }
    Log::debug("第３ステップ完了");

    $tags = $request->values['tags'];

    $tagsData = [];
    $articleTagsData = [];
    foreach ($tags as $tag) {
      if ($tag !== null) {
        $tag_data = Tag::firstOrCreate(['name' => $tag["tag"]]);
        $tagsData[] = $tag_data;

        $articleTagsData[] = ArticleOfItemTag::create([
          'article_of_item_id' => $article->id,
          'tag_id' => $tag_data->id
        ]);
      }
    }
    Log::debug("第４ステップ完了");


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
   * Display the specified resource.
   */
  public function show(string $id)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(string $id)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, string $id)
  {
    //
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(string $id)
  {
    //
  }
}
