<?php

namespace App\Http\Controllers;

use App\Models\ArticleOfItem;
use App\Models\ArticleOfRecipe;
use App\Models\Bookshelf;
use App\Models\BookshelfArticleOfItem;
use App\Models\BookshelfArticleOfRecipe;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class BookshelfController extends Controller
{
  public function getBookshelves(string $id): JsonResponse
  {
    Log::debug("getBookshelves");
    Log::debug($id);
    $bookshelves = Bookshelf::where("user_id", $id)->orderBy('updated_at', 'desc')->paginate(20);
    Log::debug($bookshelves);

    return response()->json($bookshelves);
  }

  public function create(string $id, Request $request): JsonResponse
  {
    Log::debug("create");

    $bookshelf = Bookshelf::create([
      "user_id" => $id,
      "name" => $request->name
    ]);

    return response()->json($bookshelf);
  }

  public function getBookshelfArticles(string $id): JsonResponse
  {
    Log::debug("getBookshelfArticles");
    $bookshelf = Bookshelf::find($id);

    $articlesOfRecipePivots = BookshelfArticleOfRecipe::where("bookshelf_id", $id)->get();
    $articlesOfItemPivots = BookshelfArticleOfItem::where("bookshelf_id", $id)->get();

    $articlesWithUser = [];
    foreach ($articlesOfRecipePivots as $articlesOfRecipePivot) {
      $articlesWithUser[] = ArticleOfRecipe::with("user")->find($articlesOfRecipePivot["article_of_recipe_id"]);
    }

    foreach ($articlesOfItemPivots as $articlesOfItemPivot) {
      $articlesWithUser[] = ArticleOfItem::with("user")->find($articlesOfItemPivot["article_of_item_id"]);
    }

    // 配列をコレクションに変換
    $collectionArticlesWithUser = new Collection($articlesWithUser);
    // updated_at でソート
    $sorted = $collectionArticlesWithUser->sortByDesc('updated_at');

    // ページネーションを適用
    $perPage = 20;
    $page = Paginator::resolveCurrentPage() ?: 1;
    $paginated = new LengthAwarePaginator($sorted->forPage($page, $perPage), $sorted->count(), $perPage, $page);

    Log::debug($paginated);

    $response = [
      "bookshelf" => $bookshelf,
      "pagination" => $paginated
    ];

    return response()->json($response);
  }

  public function storeArticle(Request $request): JsonResponse
  {
    Log::debug("storeArticle");

    if ($request->article_type === "ArticleOfRecipe") {
      $pivot = BookshelfArticleOfRecipe::create([
        "bookshelf_id" => $request->bookshelf_id,
        "article_of_recipe_id" => $request->article_id
      ]);

      return response()->json($pivot);
    }

    $pivot = BookshelfArticleOfItem::create([
      "bookshelf_id" => $request->bookshelf_id,
      "article_of_item_id" => $request->article_id
    ]);

    return response()->json($pivot);
  }

  public function deleteFavorites(string $id, Request $request): JsonResponse
  {
    Log::debug("deleteFavorites");
    Log::debug($request);
    $array = json_decode($request->getContent(), true);

    foreach ($array as $data) {
      Log::debug($data);
      $type = $data["type"] === "recipe" ? "article_of_recipe_id" : "article_of_item_id";
      BookshelfArticleOfItem::where([
        "bookshelf_id" => $id,
        $type => $data["id"]
      ])->delete();
    }

    return response()->json("削除しました");
  }

  public function deleteBookshelf(string $id): JsonResponse
  {
    Log::debug("deleteBookshelf");

    $bookshelf = Bookshelf::find($id);

    BookshelfArticleOfRecipe::where("bookshelf_id", $id)->delete();
    BookshelfArticleOfItem::where("bookshelf_id", $id)->delete();
    $bookshelf->delete();

    return response()->json("削除しました");
  }
}
