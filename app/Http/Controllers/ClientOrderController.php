<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClientOrder\ClientOrderStoreRequest;
use App\Http\Resources\ClientOrder\ClientOrderIndexResource;
use App\Services\ClientOrderService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ClientOrderController extends Controller
{
    public function __construct(
        protected ClientOrderService $clientOrderService
    ) {}

    /**
     * @param ClientOrderStoreRequest $request
     * @return JsonResponse
     */
    public function store(ClientOrderStoreRequest $request): JsonResponse
    {
        try {
            $validatedData = $request->validated();
            $this->clientOrderService->addClientOrder($validatedData);
            return response()->json([ 'message' => 'Success' ], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json([ 'message' => $e->getMessage() ], $e->getCode());
        }
    }

    /**
     * @return ClientOrderIndexResource|JsonResponse
     */
    public function index(): JsonResponse|ClientOrderIndexResource
    {
        try {
            $clientOrders = $this->clientOrderService->getClientOrders();
            return new ClientOrderIndexResource($clientOrders);
        } catch (Exception $e) {
            return response()->json([ 'message' => $e->getMessage() ], $e->getCode());
        }
    }
}
