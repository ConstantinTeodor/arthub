<?php

use App\Http\Controllers\ArtworkController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\TypeController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::prefix('auth')->group(function () {
    Route::post('/login', [UserController::class, 'login']);
    Route::post('/logout', [UserController::class, 'logout']);
    Route::post('/register', [UserController::class, 'store']);
});

Route::group(['middleware' => ['auth:api']], function () {
    Route::prefix('clients')->group(function () {
        Route::post('/', [ClientController::class, 'store']);
    });

    Route::prefix('artworks')->group(function () {
        Route::post('/', [ArtworkController::class, 'store']);
        Route::post('/images', [ArtworkController::class, 'upload']);
    });

    Route::prefix('posts')->group(function () {
        Route::post('/', [PostController::class, 'store']);
    });

    Route::prefix('genres')->group(function () {
        Route::get('/', [GenreController::class, 'index']);
    });

    Route::prefix('types')->group(function () {
        Route::get('/', [TypeController::class, 'index']);
    });
});
