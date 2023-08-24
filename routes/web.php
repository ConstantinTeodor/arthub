<?php

use App\Http\Controllers\ArtworkController;
use App\Http\Controllers\AuctionController;
use App\Http\Controllers\ClientCartController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ClientOrderController;
use App\Http\Controllers\ConnectionController;
use App\Http\Controllers\ConversationController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\NotificationController;
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
        Route::get('/{id}', [ClientController::class, 'show']);
        Route::get('/myAccount/myId', [ClientController::class, 'getMyId']);
        Route::get('/checkout/userdata', [ClientController::class, 'getCheckoutData']);
        Route::get('/search/{string}', [ClientController::class, 'search']);
        Route::put('/', [ClientController::class, 'update']);
    });

    Route::prefix('artworks')->group(function () {
        Route::post('/', [ArtworkController::class, 'store']);
        Route::post('/images', [ArtworkController::class, 'upload']);
        Route::get('/images/{id}', [ArtworkController::class, 'download']);
        Route::get('/artists', [ArtworkController::class, 'getArtists']);
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
        Route::get('/feed/getIds', [PostController::class, 'feed']);
    });

    Route::prefix('auctions')->group(function () {
        Route::post('/', [AuctionController::class, 'store']);
        Route::post('/filtered', [AuctionController::class, 'filtered']);
        Route::get('/{id}', [AuctionController::class, 'show']);
        Route::post('/participate', [AuctionController::class, 'participate']);
        Route::post('/bid', [AuctionController::class, 'bid']);
        Route::get('/', [AuctionController::class, 'index']);
    });

    Route::prefix('sales')->group(function () {
        Route::post('/', [SaleController::class, 'store']);
        Route::get('/latest', [SaleController::class, 'latest']);
        Route::post('/all', [SaleController::class, 'getFiltered']);
    });

    Route::prefix('cart')->group(function () {
        Route::post('/', [ClientCartController::class, 'addToCart']);
        Route::get('/', [ClientCartController::class, 'show']);
        Route::delete('/{id}', [ClientCartController::class, 'delete']);
        Route::put('/quantity', [ClientCartController::class, 'updateQuantity']);
        Route::get('/total', [ClientCartController::class, 'getTotal']);
    });

    Route::prefix('/orders')->group(function () {
        Route::post('/', [ClientOrderController::class, 'store']);
        Route::get('/', [ClientOrderController::class, 'index']);
    });

    Route::prefix('genres')->group(function () {
        Route::get('/', [GenreController::class, 'index']);
    });

    Route::prefix('types')->group(function () {
        Route::get('/', [TypeController::class, 'index']);
    });

    Route::prefix('connections')->group(function () {
        Route::post('/', [ConnectionController::class, 'store']);
        Route::get('/status/{receiver_id}', [ConnectionController::class, 'status']);
        Route::delete('/{id}', [ConnectionController::class, 'destroy']);
        Route::put('/', [ConnectionController::class, 'update']);
        Route::get('/', [ConnectionController::class, 'index']);
    });

    Route::prefix('notifications')->group(function () {
        Route::get('/', [NotificationController::class, 'index']);
        Route::post('/read', [NotificationController::class, 'markAsRead']);
        Route::post('/readAll', [NotificationController::class, 'readAll']);
    });

    Route::prefix('conversations')->group(function () {
        Route::get('/', [ConversationController::class, 'index']);
        Route::put('/{id}', [ConversationController::class, 'update']);

    });

    Route::prefix('messages')->group(function () {
        Route::get('/{id}', [MessageController::class, 'show']);
        Route::post('/', [MessageController::class, 'store']);
    });
});

Route::get('/verify-account/{token}', [ClientController::class, 'verifyAccount']);
Route::get('/forgot-password/{string}', [UserController::class, 'forgotPassword']);
Route::put('/user/reset', [UserController::class, 'updateRecoveryPassword']);
