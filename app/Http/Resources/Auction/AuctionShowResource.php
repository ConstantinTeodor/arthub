<?php

namespace App\Http\Resources\Auction;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class AuctionShowResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $startDate = Carbon::parse($this->start_date);
        $endDate = Carbon::parse($this->end_date);

        if ($startDate->isBefore(now())) {
            $started = true;
        } else {
            $started = false;
        }

        if ($endDate->isBefore(now())) {
            $ended = true;
        } else {
            $ended = false;
        }

        if ($this->participate === false) {
            return [
                'title' => $this->artwork->title,
                'artist' => $this->artwork->artist,
                'image' => $this->image,
                'start_bid' => $this->start_bid,
                'current_bid' => $this->current_bid,
                'name' => $this->name,
                'started' => $started,
                'ended' => $ended,
                'start_date' => $startDate->format('d F, Y: H:i'),
                'end_date' => $endDate->format('d F, Y: H:i'),
                'not_formatted_start_date' => $this->start_date,
                'not_formatted_end_date' => $this->end_date,
                'participating' => 'false',
            ];
        } else {
            return [
                'title' => $this->artwork->title,
                'artist' => $this->artwork->artist,
                'image' => $this->image,
                'start_bid' => $this->start_bid,
                'current_bid' => $this->current_bid,
                'name' => $this->name,
                'started' => $started,
                'ended' => $ended,
                'start_date' => $startDate->format('d F, Y: H:i'),
                'end_date' => $endDate->format('d F, Y: H:i'),
                'not_formatted_start_date' => $this->start_date,
                'not_formatted_end_date' => $this->end_date,
                'participating' => 'true',
                'available_sum' => $this->clientAuction->available_sum,
                'bid' => $this->clientAuction->bid,
            ];
        }
    }
}
