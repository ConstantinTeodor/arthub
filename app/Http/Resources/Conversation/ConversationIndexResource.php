<?php

namespace App\Http\Resources\Conversation;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ConversationIndexResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = [];

        foreach ($this->resource as $conversation) {
            foreach ($conversation['clients'] as $client) {
                if ($conversation['myId'] == $client['id']) {
                    continue;
                }

                if ($client['middle_name'] === null) {
                    $with = $client['first_name'] . ' ' . $client['last_name'];
                } else {
                    $with = $client['first_name'] . ' ' . $client['middle_name'] . ' ' . $client['last_name'];
                }
            }
            $data[] = [
                'id' => $conversation['id'],
                'name' => $conversation['name'],
                'with' => $with,
                'unread_messages' => $conversation['unread_messages'],
            ];
        }

        return $data;
    }
}
