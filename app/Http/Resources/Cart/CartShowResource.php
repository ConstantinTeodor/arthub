<?php

namespace App\Http\Resources\Cart;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartShowResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = [];

        foreach ($this['data'] as $cartItem) {
            $data[] = [
                'client_id' => $cartItem->client_id,
                'artwork_id' => $cartItem->artwork_id,
                'price' => $cartItem->total_amount,
                'quantity' => $cartItem->quantity,
                'artist' => $cartItem->artworkData->artist,
                'title' => $cartItem->artworkData->title,
                'image' => $cartItem->postData->id,
                'available' => $cartItem->available,
                'base_price' => $cartItem->basePrice,
            ];
        }

        return $data;
    }
}
