<?php

use App\Http\Controllers\FoodItemController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\MapController;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\SocialController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
  return $request->user();
});

Route::prefix('recipes')
  ->name('recipes.')
  ->controller(RecipeController::class)
  ->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('/search', 'search')->name('search');
    Route::get('/{id}', 'get')->name('get');
    Route::post('/', 'store')->name('store');
    Route::post('/{id}/comment', 'commentStore')->name('commentStore');
    Route::delete('/comment', 'commentDelete')->name('commentDelete');
    Route::put('/{id}', 'update')->name('update');
    Route::delete('/{id}', 'delete')->name('delete');
  });

Route::prefix('food_items')
  ->name('food_items.')
  ->controller(FoodItemController::class)
  ->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('/search', 'search')->name('search');
    Route::get('/{id}', 'get')->name('get');
    Route::post('/', 'store')->name('store');
    Route::post('/{id}/comment', 'commentStore')->name('commentStore');
    Route::delete('/comment', 'commentDelete')->name('commentDelete');
    Route::put('/{id}', 'update')->name('update');
    Route::delete('/{id}', 'delete')->name('delete');
  });

Route::prefix('likes')
  ->name('likes.')
  ->controller(LikeController::class)
  ->group(function () {
    Route::put('/{id}', 'update')->name('update');
  });

Route::prefix('maps')
  ->name('maps.')
  ->controller(MapController::class)
  ->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('/search', 'search')->name('search');
    Route::get('/{id}', 'get')->name('get');
    Route::post('/', 'store')->name('store');
    Route::post('/{id}/comment', 'commentStore')->name('commentStore');
    Route::put('/{id}', 'update')->name('update');
    Route::delete('/{id}', 'delete')->name('delete');
  });

Route::group(['middleware' => ['session']], function () {
  Route::get('login/{provider}', [SocialController::class, 'redirect']);
  Route::get('login/{provider}/callback', [SocialController::class, 'callback']);
});
