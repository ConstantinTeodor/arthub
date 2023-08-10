<?php

namespace App\Http\Controllers;

use App\Http\Requests\ArtworkStoreRequest;
use App\Services\ArtworkService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
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
            $artworkId = $this->artworkService->addArtwork($validatedData);
            return response()->json([ 'message' => 'Success', 'id' => $artworkId ], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json([ 'message' => $e->getMessage() ], $e->getCode());
        }
    }

    public function upload(Request $request)
    {
        try {
            $validatedData = $request->validate([ 'file' => 'required|image|mimes:jpeg,png,jpg|max:2048' ]);
            $filename = $this->artworkService->uploadFile($validatedData);
            return response()->json(['message' => 'Success', 'filename' => $filename], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], $e->getCode());
        }
    }
}
