<?php

namespace App\Http\Resources\Post;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostShowResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $postComments = [];
        foreach ($this->comments as $comment) {
            $postComments[] = [
                'id' => $comment->id,
                'username' => $comment->user->name,
                'comment' => $comment->comment,
                'posted_at' => $comment->created_at->format('d F, Y: H:i'),
                'likes' => count($comment->likes),
                'myComment' => $comment->myComment,
            ];
        }

        $saleData = [];

        if ($this->sale !== []) {
            $saleData = [
                'id' => $this->sale->id,
                'price' => $this->sale->price,
                'quantity' => $this->sale->quantity,
            ];
        }

        $auctionData = [];

        if ($this->auction !== []) {
            $auctionData = [
                'id' => $this->auction->id,
                'name' => $this->auction->name,
                'start_date' => $this->auction->start_date,
                'end_date' => $this->auction->end_date,
                'start_bid' => $this->auction->start_bid,
            ];
        }

        $genres = [];

        foreach ($this->genres as $genre) {
            $genres[] = [
                $genre->name,
            ];
        }

        $types = [];

        foreach ($this->types as $type) {
            $types[] = [
                $type->name,
            ];
        }

        return [
            'id' => $this->id,
            'artwork_title' => $this->artwork->title,
            'artwork_artist' => $this->artwork->artist,
            'description' => $this->description,
            'username' => $this->user->name,
            'no_likes' => count($this->likes),
            'no_comments' => count($this->comments),
            "comments" => $postComments,
            'posted_at' => $this->created_at->format('d F, Y: H:i'),
            'myPost' => $this->myPost,
            'sale' => $saleData !== [] ? $saleData : null,
            'auction' => $auctionData !== [] ? $auctionData : null,
            'genres' => $genres,
            'types' => $types,
        ];
    }
}
