<?php

namespace App\Http\Controllers;

use App\Http\Requests\Sale\SaleStoreRequest;
use App\Services\SaleService;
use Illuminate\Http\JsonResponse;

class SaleController extends Controller
{
    public function __construct(
        protected SaleService $saleService,
    ) {}

    /**
     * @param SaleStoreRequest $request
     * @return JsonResponse
     */
    public function store(SaleStoreRequest $request): JsonResponse
    {
        try {
            $this->saleService->addSale($request->validated());
            return response()->json(['message' => 'Sale created successfully'], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Sale creation failed'], 500);
        }
    }
}
