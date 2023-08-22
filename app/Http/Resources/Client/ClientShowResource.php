<?php

namespace App\Http\Resources\Client;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClientShowResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        if ($this->isMe) {
            return [
                'id' => $this->id,
                'first_name' => $this->first_name,
                'middle_name' => $this->middle_name,
                'last_name' => $this->last_name,
                'username' => $this->user->name,
                'email' => $this->user->email,
                'phone' => $this->user->phone_number,
                'date_of_birth' => $this->date_of_birth,
                'posts' => $this->posts,
                'no_posts' => count($this->posts),
                'me' => true
            ];
        }

        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'middle_name' => $this->middle_name,
            'last_name' => $this->last_name,
            'username' => $this->user->name,
            'posts' => $this->posts,
            'no_posts' => count($this->posts),
            'me' => false
        ];
    }
}
