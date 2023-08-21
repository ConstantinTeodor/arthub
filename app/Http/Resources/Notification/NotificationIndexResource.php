<?php

namespace App\Http\Resources\Notification;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationIndexResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $unreadNotifications = 0;
        foreach ($this->resource as $notification) {
            if ($notification->status === 'unread') {
                $unreadNotifications++;
            }
        }

        return [
            'notifications' => $this->resource,
            'unread_notifications' => $unreadNotifications,
        ];
    }
}
