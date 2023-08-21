<?php

namespace App\Http\Resources\Auction;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuctionFilteredResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = [];

        foreach ($this['data'] as $auction) {
            if ($auction->deleted_at !== null || $auction->winned_id !== null) continue;
            $data[] = [
                'id' => $auction->id,
                'artwork_id' => $auction->artwork_id,
                'listed_by' => $auction->client->id,
                'listed_by_name' => $auction->client->first_name . ' ' . $auction->client->last_name,
                'start_bid' => $auction->start_bid,
                'current_bid' => $auction->current_bid,
                'artist' => $auction->artist,
                'title' => $auction->title,
                'image' => $auction->image,
                'start_date' => $auction->start_date,
                'end_date' => $auction->end_date,
            ];
        }

        return $data;
    }
}
