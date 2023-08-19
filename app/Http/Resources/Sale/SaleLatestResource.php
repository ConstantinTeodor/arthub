<?php

namespace App\Http\Resources\Sale;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SaleLatestResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = [];

        foreach ($this['data'] as $sale) {
            if ($sale->deleted_at !== null || $sale->quantity == 0) continue;
            $data[] = [
                'id' => $sale->id,
                'artwork_id' => $sale->artwork->id,
                'listed_by' => $sale->client->id,
                'listed_by_name' => $sale->client->first_name . ' ' . $sale->client->last_name,
                'price' => $sale->price,
                'quantity' => $sale->quantity,
                'artist' => $sale->artwork->artist,
                'title' => $sale->artwork->title,
                'image' => $sale->image,
            ];
        }

        return $data;
    }
}
