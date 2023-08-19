<?php

namespace App\Services;

use App\Models\Artwork;
use App\Models\ClientCart;
use App\Models\Post;
use App\Models\Sale;
use App\Models\User;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ClientCartService
{
    public function __construct() {}

    /**
     * @param array $data
     * @return void
     * @throws Exception
     */
    public function addToCart(array $data): void
    {
        if (Auth::check()) {
            $user = User::findOrFail(Auth::user()->id);
        } else {
            throw new Exception('Unauthorized', Response::HTTP_UNAUTHORIZED);
        }

        $client = $user->client;

        if (empty($client)) {
            throw new Exception('Client not found', Response::HTTP_NOT_FOUND);
        }

        $cart = ClientCart::where('client_id', '=', $client->id)->where('artwork_id', '=', $data['artwork_id'])->first();

        if (!empty($cart)) {
            $sale = Sale::where('artwork_id', '=', $data['artwork_id'])->first();
            if ($sale->quantity > $cart->quantity) {
                $cart->quantity += 1;
                $cart->total_amount += $data['price'];
                $cart->save();
            } else {
                throw new Exception('Not enough stock', Response::HTTP_BAD_REQUEST);
            }
        }

        $cart = new ClientCart();
        $cart->client()->associate($client);
        $cart->artwork_id = $data['artwork_id'];
        $cart->quantity = 1;
        $cart->total_amount = $data['price'];
        $cart->save();
    }

    /**
     * @throws Exception
     */
    public function showCart()
    {
        if (Auth::check()) {
            $user = User::findOrFail(Auth::user()->id);
        } else {
            throw new Exception('Unauthorized', Response::HTTP_UNAUTHORIZED);
        }

        $client = $user->client;

        if (empty($client)) {
            throw new Exception('Client not found', Response::HTTP_NOT_FOUND);
        }

        $cartItems = ClientCart::where('client_id', '=', $client->id)->get();

        if (empty($cartItems)) {
            return [];
        }

        foreach ($cartItems as $item) {
            $artwork = Artwork::findOrFail($item->artwork_id);
            $post = Post::where('artwork_id', '=', $artwork->id)->first();
            $item->artworkData = $artwork;
            $sale = Sale::where('artwork_id', '=', $artwork->id)->first();
            $item->available = $sale->quantity;
            $item->basePrice = $sale->price;
            $item->postData = $post;
        }

        return $cartItems;
    }

    /**
     * @param int $artwork_id
     * @return void
     * @throws Exception
     */
    public function delete(int $artwork_id): void
    {
        if (Auth::check()) {
            $user = User::findOrFail(Auth::user()->id);
        } else {
            throw new Exception('Unauthorized', Response::HTTP_UNAUTHORIZED);
        }

        $client = $user->client;

        if (empty($client)) {
            throw new Exception('Client not found', Response::HTTP_NOT_FOUND);
        }

        ClientCart::where('client_id', '=', $client->id)->where('artwork_id', '=', $artwork_id)->delete();
    }

    /**
     * @param array $data
     * @return void
     * @throws Exception
     */
    public function updateQuantity(array $data): void
    {
        if (Auth::check()) {
            $user = User::findOrFail(Auth::user()->id);
        } else {
            throw new Exception('Unauthorized', Response::HTTP_UNAUTHORIZED);
        }

        $client = $user->client;

        if (empty($client)) {
            throw new Exception('Client not found', Response::HTTP_NOT_FOUND);
        }

        DB::table('client_cart_items')
            ->where('client_id', '=', $client->id)
            ->where('artwork_id', '=', $data['artwork_id'])
            ->update(['quantity' => $data['quantity'], 'total_amount' => $data['total_amount']]);
    }

    /**
     * @return mixed
     * @throws Exception
     */
    public function getTotal(): mixed
    {
        if (Auth::check()) {
            $user = User::findOrFail(Auth::user()->id);
        } else {
            throw new Exception('Unauthorized', Response::HTTP_UNAUTHORIZED);
        }

        $client = $user->client;

        if (empty($client)) {
            throw new Exception('Client not found', Response::HTTP_NOT_FOUND);
        }

        return ClientCart::where('client_id', '=', $client->id)->sum('total_amount');
    }
}
