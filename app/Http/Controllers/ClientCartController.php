<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClientCart\ClientCartAddRequest;
use App\Http\Requests\ClientCart\ClientCartUpdateQuantityRequest;
use App\Http\Resources\Cart\CartShowResource;
use App\Services\ClientCartService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class ClientCartController extends Controller
{
    public function __construct(
        protected ClientCartService $clientCartService
    ) {}

    /**
     * @param ClientCartAddRequest $request
     * @return JsonResponse
     */
    public function addToCart(ClientCartAddRequest $request): JsonResponse
    {
        try {
            $validatedData = $request->validated();
            $this->clientCartService->addToCart($validatedData);
            return response()->json(['message' => 'Sale added to cart successfully'], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], $e->getCode());
        }
    }

    /**
     * @return CartShowResource|JsonResponse
     */
    public function show(): CartShowResource|JsonResponse
    {
        try {
            $cart = $this->clientCartService->showCart();
            return new CartShowResource(['data' => $cart]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], $e->getCode());
        }
    }

    /**
     * @param int $artwork_id
     * @return JsonResponse
     */
    public function delete(int $artwork_id): JsonResponse
    {
        try {
            $this->clientCartService->delete($artwork_id);
            return response()->json(['message' => 'Sale deleted from cart successfully'], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], $e->getCode());
        }
    }

    public function updateQuantity(ClientCartUpdateQuantityRequest $request)
    {
        try {
            $validatedData = $request->validated();
            $this->clientCartService->updateQuantity($validatedData);
            return response()->json(['message' => 'Quantity updated successfully'], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], $e->getCode());
        }
    }

    /**
     * @return JsonResponse
     */
    public function getTotal(): JsonResponse
    {
        try {
            $total = $this->clientCartService->getTotal();
            return response()->json(['total' => $total], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], $e->getCode());
        }
    }
}
