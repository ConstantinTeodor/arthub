<?php

namespace App\Http\Controllers;

use App\Http\Requests\Connection\ConnectionStoreRequest;
use App\Http\Requests\Connection\ConnectionUpdateRequest;
use App\Services\ControllerService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class ConnectionController extends Controller
{
    public function __construct(
        protected ControllerService $controllerService
    )
    {
    }

    /**
     * @param ConnectionStoreRequest $request
     * @return JsonResponse
     * @throws Exception
     */
    public function store(ConnectionStoreRequest $request): JsonResponse
    {
        try {
            $validatedData = $request->validated();
            $this->controllerService->addConnection($validatedData);
            return response()->json(['message' => 'Success'], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], $e->getCode());
        }
    }

    /**
     * @param int $receiver_id
     * @return JsonResponse
     */
    public function status(int $receiver_id): JsonResponse
    {
        try {
            $status = $this->controllerService->getConnectionStatus($receiver_id);
            return response()->json(['status' => $status], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], $e->getCode());
        }
    }

    /**
     * @param int $receiver_id
     * @return JsonResponse
     */
    public function destroy(int $receiver_id): JsonResponse
    {
        try {
            $this->controllerService->deleteConnection($receiver_id);
            return response()->json(['message' => 'Success'], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], $e->getCode());
        }
    }

    /**
     * @param ConnectionUpdateRequest $request
     * @return JsonResponse
     */
    public function update(ConnectionUpdateRequest $request): JsonResponse
    {
        try {
            $validatedData = $request->validated();
            $this->controllerService->updateConnection($validatedData);
            return response()->json(['message' => 'Success'], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], $e->getCode());
        }
    }
}
