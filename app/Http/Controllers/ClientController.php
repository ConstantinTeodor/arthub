<?php

namespace App\Http\Controllers;

use App\Http\Requests\Client\ClientStoreRequest;
use App\Http\Resources\Client\ClientCheckoutResource;
use App\Http\Resources\Client\ClientSearchResource;
use App\Http\Resources\Client\ClientShowResource;
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

    /**
     * @param int $id
     * @return ClientShowResource|JsonResponse
     */
    public function show(int $id): JsonResponse|ClientShowResource
    {
        try {
            $client = $this->clientService->getClientData($id);
            return new ClientShowResource($client);
        } catch (Exception $e) {
            return response()->json([ 'message' => $e->getMessage() ], $e->getCode());
        }
    }

    /**
     * @return JsonResponse
     */
    public function getMyId(): JsonResponse
    {
        try {
            $myId = $this->clientService->myId();
            return response()->json([ 'id' => $myId ], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json([ 'message' => $e->getMessage() ], $e->getCode());
        }
    }

    /**
     * @return ClientCheckoutResource|JsonResponse
     */
    public function getCheckoutData(): ClientCheckoutResource|JsonResponse
    {
        try {
            $checkoutData = $this->clientService->getCheckoutData();
            return new ClientCheckoutResource($checkoutData);
        } catch (Exception $e) {
            return response()->json([ 'message' => $e->getMessage() ], $e->getCode());
        }
    }

    /**
     * @param string $query
     * @return ClientSearchResource|JsonResponse
     */
    public function search(string $query): ClientSearchResource|JsonResponse
    {
        try {
            $clients = $this->clientService->search($query);
            return new ClientSearchResource(['data' => $clients]);
        } catch (Exception $e) {
            return response()->json([ 'message' => $e->getMessage() ], $e->getCode());
        }
    }
}
