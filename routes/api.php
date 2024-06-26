<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookshelfController;
use App\Http\Controllers\FoodItemController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\MapController;
use App\Http\Controllers\RecipeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('recipes')
  ->name('recipes.')
  ->controller(RecipeController::class)
  ->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('/search', 'search')->name('search');
    Route::get('/{id}', 'get')->name('get');

    Route::middleware(['auth:sanctum'])
      ->group(function () {
        Route::post('/', 'store')->name('store');
        Route::post('/{id}/comment', 'commentStore')->name('commentStore');
        Route::delete('/comment', 'commentDelete')->name('commentDelete');
        Route::put('/{id}', 'update')->name('update');
        Route::delete('/{id}', 'delete')->name('delete');
      });
  });

Route::prefix('food_items')
  ->name('food_items.')
  ->controller(FoodItemController::class)
  ->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('/search', 'search')->name('search');
    Route::get('/{id}', 'get')->name('get');

    Route::middleware(['auth:sanctum'])
      ->group(function () {
        Route::post('/', 'store')->name('store');
        Route::post('/{id}/comment', 'commentStore')->name('commentStore');
        Route::delete('/comment', 'commentDelete')->name('commentDelete');
        Route::put('/{id}', 'update')->name('update');
        Route::delete('/{id}', 'delete')->name('delete');
      });
  });

Route::prefix('likes')
  ->name('likes.')
  ->controller(LikeController::class)
  ->middleware(['auth:sanctum'])
  ->group(function () {
    Route::put('/{id}', 'update')->name('update');
  });

Route::prefix('maps')
  ->name('maps.')
  ->controller(MapController::class)
  ->group(function () {
    Route::middleware(['session'])
      ->group(function () {
        Route::get('/reviews/{id}', 'get')->name('get');
        Route::post('/reviews/search', 'search')->name('search');
      });
    Route::middleware(['auth:sanctum'])
      ->group(function () {
        Route::post('/reviews', 'store')->name('store');
        Route::delete('/reviews/{id}', 'delete')->name('delete');
      });
  });

Route::prefix('user')
  ->name('user.')
  ->controller(AuthController::class)
  ->group(function () {
    Route::middleware(['session'])
      ->group(function () {
        Route::get('/get/{id}', 'getUser')->name('getUser');
        Route::get('/articles/{id}', 'getUserArticles')->name('getUserArticles');
        Route::post('/login', 'login')->name('login');
        Route::post('/register', 'register')->name('register');
        Route::post('/check_account_id', 'checkAccountId')->name('checkAccountId');
        Route::post('/forget_password/search_user', 'searchUser')->name('searchUser');
        Route::post('/forget_password/reset/{id}', 'passwordReset')->name('passwordReset');
        Route::get('auth/{provider}', 'redirect')->name('redirect');
        Route::get('auth/{provider}/callback', 'callback')->name('callback');
      });
    Route::middleware(['auth:sanctum'])
      ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/logout', 'logout')->name('logout');
        Route::get('/{id}', 'getArticles')->name('getArticles');
        Route::put('/update/{id}', 'update')->name('update');
        Route::post('/password/reset/{id}', 'passwordReset')->name('passwordReset');
        Route::delete('/delete/account/{id}', 'deleteAccount')->name('deleteFavorites');
      });
  });

Route::prefix('bookshelves')
  ->name('bookshelves.')
  ->controller(BookshelfController::class)
  ->middleware(['auth:sanctum'])
  ->group(function () {
    Route::get('/{id}', 'getBookshelves')->name('getBookshelves');
    Route::post('/create/{id}', 'create')->name('create');
    Route::get('/bookshelf/{id}', 'getBookshelfArticles')->name('getBookshelfArticles');
    Route::post('/store_article', 'storeArticle')->name('storeArticle');
    Route::delete('/delete/bookshelf/{id}', 'deleteBookshelf')->name('deleteBookshelf');
    Route::delete('/delete/favorites/{id}', 'deleteFavorites')->name('deleteFavorites');
  });
