<?php

namespace App\Http\Controllers;

use App\Http\Requests\Client\ClientStoreRequest;
use App\Services\ClientService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class ClientController extends Controller
{
    public function __construct (
        protected ClientService $clientService
    ) {}

    /**
     * @param ClientStoreRequest $request
     * @return JsonResponse
     */
    public function store(ClientStoreRequest $request): JsonResponse
    {
        try {
            $validatedData = $request->validated();
            $this->clientService->addClient($validatedData);
            return response()->json([ 'message' => 'Success' ], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json([ 'message' => $e->getMessage() ], $e->getCode());
        }
    }
}
