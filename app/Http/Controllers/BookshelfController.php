<?php

namespace App\Http\Controllers;

use App\Models\ArticleOfItem;
use App\Models\ArticleOfRecipe;
use App\Models\Bookshelf;
use App\Models\BookshelfArticleOfItem;
use App\Models\BookshelfArticleOfRecipe;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class BookshelfController extends Controller
{
  public function getBookshelves(string $id)
  {
    Log::debug("getBookshelves");
    Log::debug($id);
    $bookshelves = Bookshelf::where("user_id", $id)->orderBy('updated_at', 'desc')->paginate(20);
    Log::debug($bookshelves);

    return response()->json($bookshelves);
  }

  public function create(string $id, Request $request)
  {
    Log::debug("create");

    $bookshelf = Bookshelf::create([
      "user_id" => $id,
      "name" => $request->name
    ]);

    return response()->json($bookshelf);
  }

  public function getBookshelfArticles(string $id)
  {
    Log::debug("getBookshelfArticles");

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

    return response()->json($paginated);
  }

  public function storeArticle(Request $request)
  {
    Log::debug("storeArticle");

    if ($request->article_type === "ArticleOfRecipe") {
      $pivot = BookshelfArticleOfRecipe::create([
        "bookshelf_id" => $request->bookshelf_id,
        "article_of_recipe_id" => $request->article_id
      ]);

      return response()->json($pivot);
    } else {
      $pivot = BookshelfArticleOfItem::create([
        "bookshelf_id" => $request->bookshelf_id,
        "article_of_item_id" => $request->article_id
      ]);

      return response()->json($pivot);
    }
  }
}
