<?php

namespace App\Http\Resources\ClientOrder;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClientOrderIndexResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = [];

        foreach ($this->resource as $order) {
            if ($order->client_order_items->count() === 0) continue;

            $artworkData = [];

            foreach ($order->client_order_items as $artwork) {
                $artworkData[] = [
                    'artwork_id' => $artwork->artwork_id,
                    'quantity' => $artwork->quantity,
                    'total' => $artwork->total_amount,
                    'artwork' => [
                        'artist' => $artwork->artwork->artist,
                        'title' => $artwork->artwork->title,
                    ],
                    'image' => $artwork->image,
                ];
            }

            $data[] = [
                'id' => $order->id,
                'ordered_via' => $order->ordere_via,
                'final_amount' => $order->final_amount,
                'payment' => $order->payment,
                'address' => $order->address,
                'artworks' => $artworkData,
            ];
        }

        return $data;
    }
}
