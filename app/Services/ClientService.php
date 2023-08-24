<?php

namespace App\Services;

use App\Mail\AccountConfirmationEmail;
use App\Models\Client;
use App\Models\User;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ClientService
{
    public function __construct(
        protected UserService $userService
    )
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

        $specialUrl = Str::random(60);

        DB::table('account_verifications')
            ->insert([
                'user_id' => $user->id,
                'verification_token' => $specialUrl,
                'status' => 'pending',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);


        $completeUrl = 'http://localhost:8000/verify-account/' . $specialUrl;

        $clientName = $client->first_name . ' ' . $client->last_name;
        try {
            Mail::to($user->email)->send(new AccountConfirmationEmail($clientName , $completeUrl));
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param int $id
     * @return mixed
     * @throws Exception
     */
    public function getClientData(int $id): mixed
    {
        if (Auth::check()) {
            $user = User::findOrFail(Auth::user()->id);
        } else {
            throw new Exception('Unauthorized', Response::HTTP_UNAUTHORIZED);
        }

        $clientAuth = $user->client;

        if (empty($clientAuth)) {
            throw new Exception('Client not found', Response::HTTP_NOT_FOUND);
        }

        $client = Client::findOrFail($id);
        $client->load('user');
        $client->load(['posts' => function ($query) {
            $query->orderBy('created_at', 'desc');
        }]);

        if ($clientAuth->id === $client->id) {
            $client->isMe = true;
        } else {
            $client->isMe = false;
        }

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

    /**
     * @param string $query
     * @return mixed
     */
    public function search(string $query): mixed
    {
        $clients = Client::join('users', 'users.client_id', '=', 'clients.id')
            ->where('first_name', 'like', '%' . $query . '%')
            ->orWhere('last_name', 'like', '%' . $query . '%')
            ->orWhere('middle_name', 'like', '%' . $query . '%')
            ->orWhere('name', 'like', '%' . $query . '%')
            ->get();

        return $clients;
    }

    /**
     * @param array $data
     * @return void
     * @throws Exception
     */
    public function updateClient(array $data): void
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

        if (!empty($data['first_name'])) {
            $client->first_name = $data['first_name'];
        } else {
            throw new Exception('First name is required', Response::HTTP_BAD_REQUEST);
        }

        if (!empty($data['last_name'])) {
            $client->last_name = $data['last_name'];
        } else {
            throw new Exception('Last name is required', Response::HTTP_BAD_REQUEST);
        }

        if (!empty($data['middle_name'])) {
            $client->middle_name = $data['middle_name'];
        }

        if (!empty($data['date_of_birth'])) {
            $client->date_of_birth = Carbon::createFromFormat('d-m-Y', $data['date_of_birth']);
        } else {
            throw new Exception('Date of birth is required', Response::HTTP_BAD_REQUEST);
        }

        $client->save();

        if (!empty($data['username'])) {
            $user->name = $data['username'];
        } else {
            throw new Exception('Username is required', Response::HTTP_BAD_REQUEST);
        }

        if (!empty($data['email'])) {
            $user->email = $data['email'];
        } else {
            throw new Exception('Email is required', Response::HTTP_BAD_REQUEST);
        }

        if (!empty($data['phone'])) {
            $user->phone_number = $data['phone'];
        } else {
            throw new Exception('Phone is required', Response::HTTP_BAD_REQUEST);
        }

        if (!empty($data['password'])) {
            $passwordErrors = $this->userService->checkPassword($data['password']);

            if (!empty($passwordErrors)) {
                throw new Exception(implode('<br>', $passwordErrors), Response::HTTP_BAD_REQUEST);
            }

            $user->password = Hash::make($data['password']);
        }

        $user->save();
    }

    /**
     * @param string $token
     * @return void
     * @throws Exception
     */
    public function verifyAccount(string $token): void
    {
        $verification = DB::table('account_verifications')
            ->where('verification_token', '=', $token)
            ->first();

        if (empty($verification)) {
            throw new Exception('Verification token not found', Response::HTTP_NOT_FOUND);
        }

        $user = User::findOrFail($verification->user_id);

        if (empty($user)) {
            throw new Exception('User not found', Response::HTTP_NOT_FOUND);
        }

        $user->verified = Carbon::now();
        $user->save();

        DB::table('account_verifications')
            ->where('verification_token', '=', $token)
            ->update(['status' => 'verified']);
    }
}
