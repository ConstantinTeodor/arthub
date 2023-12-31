<?php

namespace App\Services;

use App\Models\Client;
use App\Models\Connection;
use App\Models\Conversation;
use App\Models\Notification;
use App\Models\User;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ConnectionService
{
    public function __construct() {}

    /**
     * @param array $data
     * @return void
     * @throws Exception
     */
    public function addConnection(array $data): void
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

        $connection = Connection::where('requester_id', '=', $client->id)
            ->where('receiver_id', '=', $data['receiver_id'])
            ->first();

        if (!empty($connection)) {
            throw new Exception('Connection already exists', Response::HTTP_CONFLICT);
        }

        $receiver = Client::findOrFail($data['receiver_id']);

        $connection = new Connection();
        $connection->requester()->associate($client);
        $connection->receiver()->associate($receiver);
        $connection->status = 'pending';
        $connection->save();
    }

    /**
     * @param int $receiver_id
     * @return int
     * @throws Exception
     */
    public function getConnectionStatus(int $receiver_id): int
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

        $connection = Connection::where('requester_id', '=', $client->id)
            ->where('receiver_id', '=', $receiver_id)
            ->first();

        if (empty($connection)) {
            $connection = Connection::where('requester_id', '=', $receiver_id)
                ->where('receiver_id', '=', $client->id)
                ->first();

            if (empty($connection)) {
                return 0;
            } else {
                if ($connection->status === 'pending') {
                    return 1;
                } else {
                    if ($connection->status === 'accepted') {
                        return 2;
                    } else {
                        return 5;
                    }
                }
            }
        } else {
            if ($connection->status === 'pending') {
                return 3;
            } else {
                if ($connection->status === 'accepted') {
                    return 4;
                } else {
                    return 6;
                }
            }
        }
    }

    /**
     * @param int $receiver_id
     * @return void
     * @throws Exception
     */
    public function deleteConnection(int $receiver_id): void
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

        $connection = Connection::where('requester_id', '=', $client->id)
            ->where('receiver_id', '=', $receiver_id)
            ->first();

        if (empty($connection)) {
            $connection = Connection::where('requester_id', '=', $receiver_id)
                ->where('receiver_id', '=', $client->id)
                ->where('status', '=', 'accepted')
                ->first();

            if (empty($connection)) {
                throw new Exception('Connection not found', Response::HTTP_NOT_FOUND);
            } else {
                DB::table('connections')
                    ->where('requester_id', '=', $receiver_id)
                    ->where('receiver_id', '=', $client->id)
                    ->delete();
            }
        } else {
            DB::table('connections')
                ->where('requester_id', '=', $client->id)
                ->where('receiver_id', '=', $receiver_id)
                ->delete();
        }
    }

    /**
     * @param array $data
     * @return void
     * @throws Exception
     */
    public function updateConnection(array $data): void
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

        $connection = Connection::where('requester_id', '=', $data['receiver_id'])
            ->where('receiver_id', '=', $client->id)
            ->first();

        if (empty($connection)) {
            throw new Exception('Connection not found', Response::HTTP_NOT_FOUND);
        } else {
            DB::table('connections')
                ->where('requester_id', '=', $data['receiver_id'])
                ->where('receiver_id', '=', $client->id)
                ->update(['status' => $data['status']]);



            $notification = new Notification();
            $notification->client_id = $data['receiver_id'];
            $notification->title = "Connection Request Accepted";
            $notification->message = "Your connection request to " . $client->first_name . ' ' . $client->last_name . " has been accepted";
            $notification->from_id = $client->id;
            $notification->save();

            $conversation = new Conversation();
            $conversation->name = 'New conversation!';
            $conversation->save();

            $conversation->clients()->attach($client->id);
            $conversation->clients()->attach($data['receiver_id']);

            $conversation->save();
        }
    }

    /**
     * @return mixed
     * @throws Exception
     */
    public function getConnections(): mixed
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

        $connections = Connection::where('requester_id', '=', $client->id)
                    ->orWhere('receiver_id', '=', $client->id)
                    ->where('status', '=', 'accepted')
                    ->get();

        foreach ($connections as $connection) {
            $connectionClient = Client::findOrFail($connection->requester_id === $client->id ? $connection->receiver_id : $connection->requester_id);
            $connection->client = $connectionClient;
        }

        return $connections;
    }
}
