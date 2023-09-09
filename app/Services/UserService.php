<?php

namespace App\Services;

use App\Mail\ContactMail;
use App\Mail\PasswordRecoveryMail;
use App\Models\User;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Str;

class UserService
{
    public function __construct()
    {}

    /**
     * @param array $data
     * @return array
     * @throws Exception
     */
    public function addUser(array $data): array
    {
        $passwordErrors = $this->checkPassword($data['password']);

        if (!empty($passwordErrors)) {
            throw new Exception(implode('<br>', $passwordErrors), Response::HTTP_BAD_REQUEST);
        }

        if (!preg_match('/[@]/',$data['email'])) {
            throw new Exception('Invalid e-mail address', Response::HTTP_BAD_REQUEST);
        }

        if (preg_match('/[A-Z]/' ,$data['phone_number']) ||
            preg_match('/[a-z]/' ,$data['phone_number']) ||
            preg_match('/[!@#$%^&*()\-_={};:,<.>]/' ,$data['phone_number'])) {
            throw new Exception('Invalid phone number', Response::HTTP_BAD_REQUEST);
        }

        $user = new User;
        $user->name = $data['username'];
        $user->email = $data['email'];
        $user->phone_number = $data['phone_number'];
        $user->password = Hash::make($data['password']);

        $user->save();

        $token = JWTAuth::fromUser($user);

        return [
            'user' => $user,
            'token' => $token
        ];
    }

    /**
     * @param $password
     * @return array
     */
    public function checkPassword($password): array
    {
        $passwordErrors = [];

        if (strlen($password) < 8) {
            $passwordErrors[] = 'Password must be at least 8 characters long';
        }

        if (!preg_match('/[A-Z]/', $password) || !preg_match('/[a-z]/', $password)) {
            $passwordErrors[] = 'Password must contain at least one uppercase and one lowercase letter';
        }

        if (!preg_match('/\d/', $password) || !preg_match('/[!@#$%^&*()\-_=+{};:,<.>]/', $password)) {
            $passwordErrors[] = 'Password must contain at least one number and one special character';
        }

        return $passwordErrors;
    }

    /**
     * @param array $data
     * @return array
     * @throws Exception
     */
    public function tryLogin(array $data): array
    {
        $user = User::where('email', $data['email'])->first();

        if (!$user) {
            throw new Exception('Invalid email address', Response::HTTP_NOT_FOUND);
        }

        if (Hash::check($data['password'], $user->password)) {
            JWTAuth::factory()->setTTL(60 * 24 * 90);
            $token = JWTAuth::fromUser($user);

            return [
                'user' => $user,
                'token' => $token,
            ];
        }

        throw new Exception('Invalid email or password', Response::HTTP_UNAUTHORIZED);
    }

    /**
     * @param string $email
     * @return void
     * @throws Exception
     */
    public function forgotPassword(string $email): void
    {
        $user = User::where('email', '=', $email)->first();

        if (empty($user)) {
            throw new Exception('Invalid email address', Response::HTTP_NOT_FOUND);
        }

        $token = Str::random(60);

        DB::table('password_resets')->insert([
            'user_id' => $user->id,
            'reset_token' => $token,
            'status' => 'pending',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        try {
            Mail::to($user->email)->send(new PasswordRecoveryMail($token));
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param array $data
     * @return void
     * @throws Exception
     */
    public function updateRecoveryPassword(array $data): void
    {
        $user = User::where('email', '=', $data['email'])->first();

        if (!$user) {
            throw new Exception('Invalid email address', Response::HTTP_NOT_FOUND);
        }

        $passwordErrors = $this->checkPassword($data['password']);

        if (!empty($passwordErrors)) {
            throw new Exception(implode('<br>', $passwordErrors), Response::HTTP_BAD_REQUEST);
        }

        $user->password = Hash::make($data['password']);
        $user->save();

        DB::table('password_resets')
            ->where('user_id', $user->id)
            ->update(['status' => 'used']);
    }

    /**
     * @param array $data
     * @return void
     * @throws Exception
     */
    public function contact(array $data): void
    {
        if (Auth::check()) {
            $user = User::findOrFail(Auth::user()->id);
        } else {
            throw new Exception('Unauthorized', Response::HTTP_UNAUTHORIZED);
        }

        try {
            Mail::to('arthub.2023@hotmail.com')->send(new ContactMail($data['message'], $user->email));
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
