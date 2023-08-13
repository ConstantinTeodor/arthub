<?php

use App\Http\Controllers\ArtworkController;
use App\Http\Controllers\AuctionController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\SaleController;
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
        Route::get('/images/{id}', [ArtworkController::class, 'download']);
    });

    Route::prefix('posts')->group(function () {
        Route::post('/', [PostController::class, 'store']);
        Route::get('/{id}', [PostController::class, 'show']);
        Route::post('/like', [PostController::class, 'like']);
        Route::get('/liked/{id}', [PostController::class, 'liked']);
        Route::post('/comment', [PostController::class, 'comment']);
        Route::post('/comment/like', [PostController::class, 'likeComment']);
        Route::get('/comment/liked/{id}', [PostController::class, 'commentLiked']);
        Route::delete('/comment/{id}', [PostController::class, 'deleteComment']);
        Route::delete('/{id}', [PostController::class, 'deletePost']);
        Route::post('/comment/edit', [PostController::class, 'editComment']);
        Route::put('/', [PostController::class, 'update']);
    });

    Route::prefix('auctions')->group(function () {
        Route::post('/', [AuctionController::class, 'store']);
    });

    Route::prefix('sales')->group(function () {
        Route::post('/', [SaleController::class, 'store']);
    });

    Route::prefix('genres')->group(function () {
        Route::get('/', [GenreController::class, 'index']);
    });

    Route::prefix('types')->group(function () {
        Route::get('/', [TypeController::class, 'index']);
    });
});
