<?php

namespace App\Http\Resources\Message;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MessageShowResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = [];

        foreach ($this->resource as $message) {
            $data[] = [
                'id' => $message['id'],
                'text' => $message['message'],
                'created_at' => $message['created_at']->format('d F, Y: H:i'),
                'isFromMe' => $message['isFromMe'],
            ];
        }

        return $data;
    }
}
