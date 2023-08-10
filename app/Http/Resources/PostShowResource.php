<?php

namespace App\Http\Resources;

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
        ];
    }
}
