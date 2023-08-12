<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auction\AuctionStoreRequest;
use App\Services\AuctionService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class AuctionController extends Controller
{
    public function __construct(
        protected AuctionService $auctionService,
    ) {}

    /**
     * @param AuctionStoreRequest $request
     * @return JsonResponse
     */
    public function store(AuctionStoreRequest $request): JsonResponse
    {
        try {
            $validatedData = $request->validated();
            $this->auctionService->addAuction($validatedData);
            return response()->json([ 'message' => 'Success' ], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json([ 'message' => $e->getMessage() ], $e->getCode());
        }
    }
}
