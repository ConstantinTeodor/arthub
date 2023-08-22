<?php

namespace App\Http\Resources\Connection;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ConnectionIndexResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = [];

        foreach ($this->resource as $connection) {
            $data[] = [
                'client_id' => $connection->client->id,
                'client_name' => $connection->client->first_name . ' ' . $connection->client->last_name,
            ];
        }

        return $data;
    }
}
