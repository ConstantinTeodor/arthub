<?php

namespace App\Services;

use App\Models\Client;
use App\Models\Post;
use App\Models\User;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ClientService
{
    public function __construct()
    {}

    /**
     * @param array $data
     * @return void
     * @throws Exception
     */
    public function addClient(array $data): void
    {
        if (Auth::check()) {
            $user = User::findOrFail(Auth::user()->id);
        } else {
            throw new Exception('Unauthorized', Response::HTTP_UNAUTHORIZED);
        }

        $client = new Client();
        $client->first_name = $data['first_name'];
        $client->last_name = $data['last_name'];
        $client->middle_name = $data['middle_name'];
        $client->date_of_birth = Carbon::createFromFormat('d-m-Y', $data['date_of_birth']);
        $client->user()->associate($user);
        $client->save();

        $user->client_id = $client->id;
        $user->save();
    }

    /**
     * @param int $id
     * @return mixed
     * @throws Exception
     */
    public function getClientData(int $id): mixed
    {
        $client = Client::findOrFail($id);
        $client->load('user');
        $client->load(['posts' => function ($query) {
            $query->orderBy('created_at', 'desc');
        }]);

        return $client;
    }

    /**
     * @return mixed
     * @throws Exception
     */
    public function myId(): mixed
    {
        if (Auth::check()) {
            $user = User::findOrFail(Auth::user()->id);
        } else {
            throw new Exception('Unauthorized', Response::HTTP_UNAUTHORIZED);
        }

        return $user->client_id;
    }

    /**
     * @return mixed
     * @throws Exception
     */
    public function getCheckoutData(): mixed
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

        $client->load('user');

        return $client;
    }

    public function search(string $query)
    {
        $clients = Client::join('users', 'users.client_id', '=', 'clients.id')
            ->where('first_name', 'like', '%' . $query . '%')
            ->orWhere('last_name', 'like', '%' . $query . '%')
            ->orWhere('middle_name', 'like', '%' . $query . '%')
            ->orWhere('name', 'like', '%' . $query . '%')
            ->get();

        return $clients;
    }
}
