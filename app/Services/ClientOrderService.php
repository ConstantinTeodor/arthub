<?php

namespace App\Services;

use App\Models\Artwork;
use App\Models\ClientCart;
use App\Models\ClientOrder;
use App\Models\ClientOrderItem;
use App\Models\Post;
use App\Models\Sale;
use App\Models\User;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ClientOrderService
{
    public function __construct() {}

    /**
     * @param array $data
     * @return void
     * @throws Exception
     */
    public function addClientOrder(array $data): void
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

        $order = new ClientOrder();
        $order->client()->associate($client);
        $order->ordered_via = $data['ordered_via'];
        $order->final_amount = $data['final_amount'];
        $order->payment = $data['payment'];
        $order->address = $data['address'];
        $order->save();

        $cartItems = ClientCart::where('client_id', '=', $client->id)->get();

        foreach ($cartItems as $cartItem) {
            $orderItem = new ClientOrderItem();
            $orderItem->client_order_id = $order->id;
            $orderItem->artwork_id = $cartItem->artwork_id;
            $orderItem->quantity = $cartItem->quantity;
            $orderItem->total_amount = $cartItem->total_amount;
            $orderItem->save();

            $sale = Sale::where('artwork_id', '=', $cartItem->artwork_id)->first();
            $sale->quantity -= $cartItem->quantity;
            $sale->save();

            DB::table('client_cart_items')
                ->where('client_id', '=', $client->id)
                ->where('artwork_id', '=', $cartItem->artwork_id)
                ->delete();

        }
    }

    /**
     * @return mixed
     * @throws Exception
     */
    public function getClientOrders(): mixed
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

        $clientOrders = ClientOrder::where('client_id', '=', $client->id)->get();

        foreach ($clientOrders as $order) {
            $order->client_order_items = ClientOrderItem::where('client_order_id', '=', $order->id)->get();
            foreach ($order->client_order_items as $orderItem) {
                $orderItem->artwork = Artwork::findOrFail($orderItem->artwork_id);
                $post = Post::where('artwork_id', '=', $orderItem->artwork_id)->first();
                $orderItem->image = $post->id;
            }
        }

        return $clientOrders;
    }
}
