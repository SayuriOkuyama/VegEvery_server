<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FoodItemController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\MapController;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\SocialController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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
    Route::get('/', 'index')->name('index');
    Route::get('/search', 'search')->name('search');
    Route::get('/{id}', 'get')->name('get');
    Route::post('/', 'store')->name('store');
    Route::post('/{id}/comment', 'commentStore')->name('commentStore');
    Route::put('/{id}', 'update')->name('update');
    Route::delete('/{id}', 'delete')->name('delete');
  });

// Route::group(['middleware' => ['session']], function () {
//   Route::get('register', [AuthController::class, 'register']);
//   Route::get('auth/{provider}', [AuthController::class, 'redirect']);
//   Route::get('auth/{provider}/callback', [AuthController::class, 'callback']);
// });
// Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
//   Log::debug($request->user());
//   // return $request->user();
// });

Route::prefix('user')
  ->name('user.')
  ->controller(AuthController::class)
  ->group(function () {
    Route::middleware(['session'])
      ->group(function () {
        Route::post('/login', 'login')->name('login');
        Route::post('/register', 'register')->name('register');
        Route::get('auth/{provider}', 'redirect')->name('redirect');
        Route::get('auth/{provider}/callback', 'callback')->name('callback');
      });
    Route::middleware(['auth:sanctum'])
      ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/logout', 'search')->name('search');
      });
  });
// Route::group(['middleware' => ['session']], function () {
//   Route::get('user/register', [AuthController::class, 'register']);
//   Route::get('user/auth/{provider}', [AuthController::class, 'redirect']);
//   Route::get('user/auth/{provider}/callback', [AuthController::class, 'callback']);
// });

// Route::prefix('user')
//   ->middleware(['auth:sanctum'])
//   ->name('user.')
//   ->controller(AuthController::class)
//   ->group(function () {
//     Route::get('/', 'index')->name('index');
//     Route::get('/search', 'search')->name('search');
//     Route::get('/{id}', 'get')->name('get');
//     Route::post('/', 'store')->name('store');
//     Route::post('/{id}/comment', 'commentStore')->name('commentStore');
//     Route::put('/{id}', 'update')->name('update');
//     Route::delete('/{id}', 'delete')->name('delete');
//   });
