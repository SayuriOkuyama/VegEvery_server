<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
  return ['Laravel' => app()->version()];
});

require __DIR__ . '/auth.php';

// Route::prefix('api/user')
//   ->name('user.')
//   ->controller(AuthController::class)
//   ->middleware(['auth:sanctum'])
//   ->group(function () {
//     Route::get('/', 'index')->name('index');
//     Route::get('/logout', 'search')->name('search');
//   });
