<?php

namespace App\Http\Resources\Sale;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SaleFilteredResource extends JsonResource
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
                'artwork_id' => $sale->artwork_id,
                'listed_by' => $sale->client->id,
                'listed_by_name' => $sale->client->first_name . ' ' . $sale->client->last_name,
                'price' => $sale->price,
                'quantity' => $sale->quantity,
                'artist' => $sale->artist,
                'title' => $sale->title,
                'image' => $sale->image,
            ];
        }

        return $data;
    }
}
