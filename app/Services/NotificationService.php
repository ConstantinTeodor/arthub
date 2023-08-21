<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class NotificationService
{
    public function __construct() {}

    /**
     * @return mixed
     * @throws Exception
     */
    public function getNotifications(): mixed
    {
        if (Auth::check()) {
            $user = User::findOrFail(Auth::user()->id);
        } else {
            throw new Exception('Unauthorized', Response::HTTP_UNAUTHORIZED);
        }

        $client = $user->client;

        if (empty($client)) {
            throw new Exception('Client not found', Response::HTTP_NOT_FOUND);
        }

        $notification = Notification::where('client_id', $client->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return $notification;
    }

    /**
     * @param array $data
     * @return void
     * @throws Exception
     */
    public function markAsRead(array $data): void
    {
        if (Auth::check()) {
            $user = User::findOrFail(Auth::user()->id);
        } else {
            throw new Exception('Unauthorized', Response::HTTP_UNAUTHORIZED);
        }

        $client = $user->client;

        if (empty($client)) {
            throw new Exception('Client not found', Response::HTTP_NOT_FOUND);
        }

        $notification = Notification::findOrFail($data['notification_id']);

        $notification->status = 'read';
        $notification->save();
    }

    /**
     * @return void
     * @throws Exception
     */
    public function readAll(): void
    {
        if (Auth::check()) {
            $user = User::findOrFail(Auth::user()->id);
        } else {
            throw new Exception('Unauthorized', Response::HTTP_UNAUTHORIZED);
        }

        $client = $user->client;

        if (empty($client)) {
            throw new Exception('Client not found', Response::HTTP_NOT_FOUND);
        }

        $notifications = Notification::where('client_id', '=', $client->id)
            ->where('status', '=', 'unread')
            ->get();

        foreach ($notifications as $notification) {
            $notification->status = 'read';
            $notification->save();
        }
    }
}
