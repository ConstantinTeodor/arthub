<?php

namespace App\Http\Controllers;

use App\Http\Requests\Sale\SaleGetFilteredRequest;
use App\Http\Requests\Sale\SaleStoreRequest;
use App\Http\Resources\Sale\SaleFilteredResource;
use App\Http\Resources\Sale\SaleLatestResource;
use App\Services\SaleService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

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
            $validatedData = $request->validated();
            $this->saleService->addSale($validatedData);
            return response()->json(['message' => 'Sale created successfully'], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json(['message' => 'Sale creation failed'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @return SaleLatestResource|JsonResponse
     */
    public function latest(): SaleLatestResource|JsonResponse
    {
        try {
            $sales = $this->saleService->getLatest();
            return new SaleLatestResource(['data' => $sales]);
        } catch (Exception $e) {
            return response()->json(['message' => 'Sale creation failed'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param SaleGetFilteredRequest $request
     * @return SaleFilteredResource|JsonResponse
     */
    public function getFiltered(SaleGetFilteredRequest $request): SaleFilteredResource|JsonResponse
    {
        try {
            $validatedData = $request->validated();
            $sales = $this->saleService->getFiltered($validatedData);
            return new SaleFilteredResource(['data' => $sales]);
        } catch (Exception $e) {
            return response()->json(['message' => 'Sale creation failed'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
