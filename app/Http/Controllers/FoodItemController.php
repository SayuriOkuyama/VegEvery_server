<?php

namespace App\Http\Controllers;

use App\Models\ArticleOfItem;
use Illuminate\Http\Request;

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

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request)
  {
    //
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
