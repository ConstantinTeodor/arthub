<?php

namespace App\Http\Controllers;

use App\Http\Requests\ArtworkStoreRequest;
use App\Services\ArtworkService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class ArtworkController extends Controller
{
    public function __construct(
        protected ArtworkService $artworkService,
    ) {}

    /**
     * @param ArtworkStoreRequest $request
     * @return JsonResponse
     */
    public function store(ArtworkStoreRequest $request): JsonResponse
    {
        try {
            $validatedData = $request->validated();
            $this->artworkService->addArtwork($validatedData);
            return response()->json([ 'message' => 'Success' ], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json([ 'message' => $e->getMessage() ], $e->getCode());
        }
    }
}
