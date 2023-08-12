<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\UserLoginRequest;
use App\Http\Requests\User\UserStoreRequest;
use App\Services\ClientService;
use App\Services\UserService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserController extends Controller
{
    public function __construct(
        protected UserService $userService,
        protected ClientService $clientService,
    ) {}

    /**
     * @param UserStoreRequest $request
     * @return JsonResponse
     */
    public function store(UserStoreRequest $request): JsonResponse
    {
        try {
            $validatedData = $request->validated();
            $user = $this->userService->addUser($validatedData);
            return response()->json([ 'user' => $user ], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json([ 'message' => $e->getMessage() ], $e->getCode());
        }
    }

    /**
     * @param UserLoginRequest $request
     * @return JsonResponse
     */
    public function login(UserLoginRequest $request): JsonResponse
    {
        try {
            $validatedData = $request->validated();
            $response = $this->userService->tryLogin($validatedData);
            return response()->json($response, Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json([ 'message' => $e->getMessage() ], $e->getCode());
        }
    }

    /**
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        JWTAuth::invalidate(JWTAuth::getToken());
        return response()->json([ 'message' => 'Success' ], Response::HTTP_OK);
    }
}
