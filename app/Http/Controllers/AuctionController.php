<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auction\AuctionBidRequest;
use App\Http\Requests\Auction\AuctionFilteredRequest;
use App\Http\Requests\Auction\AuctionParticipateRequest;
use App\Http\Requests\Auction\AuctionStoreRequest;
use App\Http\Resources\Auction\AuctionFilteredResource;
use App\Http\Resources\Auction\AuctionShowResource;
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

    /**
     * @param AuctionFilteredRequest $request
     * @return AuctionFilteredResource|JsonResponse
     */
    public function filtered(AuctionFilteredRequest $request): AuctionFilteredResource|JsonResponse
    {
        try {
            $validatedData = $request->validated();
            $auctions = $this->auctionService->getFilteredAuctions($validatedData);
            return new AuctionFilteredResource([ 'data' => $auctions]);
        } catch (Exception $e) {
            return response()->json([ 'message' => $e->getMessage() ], $e->getCode());
        }
    }

    /**
     * @param int $auctionId
     * @return AuctionShowResource|JsonResponse
     */
    public function show(int $auctionId): AuctionShowResource|JsonResponse
    {
        try {
            $auction = $this->auctionService->getAuction($auctionId);
            return new AuctionShowResource($auction);
        } catch (Exception $e) {
            return response()->json([ 'message' => $e->getMessage() ], $e->getCode());
        }
    }

    /**
     * @param AuctionParticipateRequest $request
     * @return JsonResponse
     */
    public function participate(AuctionParticipateRequest $request): JsonResponse
    {
        try {
            $validatedData = $request->validated();
            $this->auctionService->participate($validatedData);
            return response()->json([ 'message' => 'Success' ], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json([ 'message' => $e->getMessage() ], $e->getCode());
        }
    }

    /**
     * @param AuctionBidRequest $request
     * @return JsonResponse
     */
    public function bid(AuctionBidRequest $request): JsonResponse
    {
        try {
            $validatedData = $request->validated();
            $this->auctionService->bid($validatedData);
            return response()->json([ 'message' => 'Success' ], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json([ 'message' => $e->getMessage() ], $e->getCode());
        }
    }
}
