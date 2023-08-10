<?php

namespace App\Services;

use App\Models\Client;
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
}
