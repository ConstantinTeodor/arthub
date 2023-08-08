<?php

namespace App\Services;

use App\Models\User;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

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
}
