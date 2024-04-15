<?php

namespace App\Http\Controllers;

use App\Models\Bookshelf;
use App\Models\BookshelfArticleOfItem;
use App\Models\BookshelfArticleOfRecipe;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class BookshelfController extends Controller
{
  public function getBookshelves(string $id)
  {
    Log::debug($id);
    $bookshelves = Bookshelf::where("user_id", $id)->orderBy('updated_at', 'desc')->paginate(20);
    Log::debug($bookshelves);

    return response()->json($bookshelves);
  }

  public function create(Request $request)
  {
    $bookshelf = Bookshelf::create([
      "user_id" => $request->user_id,
      "name" => $request->name
    ]);
    Log::debug($bookshelf);

    return response()->json($bookshelf);
  }

  public function getBookshelfArticles(string $id)
  {
    Log::debug("getBookshelfArticles");

    $recipes = BookshelfArticleOfRecipe::with("articlesOfRecipe.user")->where("bookshelf_id", $id)->get();

    $items = BookshelfArticleOfItem::with("articlesOfItem.user")->where("bookshelf_id", $id)->get();

    // 結果を結合
    $combined = $recipes->concat($items);

    // updated_at でソート
    $sorted = $combined->sortByDesc('updated_at');

    // ページネーションを適用
    $perPage = 20;
    $page = Paginator::resolveCurrentPage() ?: 1;
    $paginated = new LengthAwarePaginator($sorted->forPage($page, $perPage), $sorted->count(), $perPage, $page);

    Log::debug($paginated);

    return response()->json($paginated);
  }
}
