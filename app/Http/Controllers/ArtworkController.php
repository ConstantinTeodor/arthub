<?php

namespace App\Http\Controllers;

use App\Http\Requests\ArtworkStoreRequest;
use App\Services\ArtworkService;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

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

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function upload(Request $request): JsonResponse
    {
        try {
            $validatedData = $request->validate([ 'file' => 'required|image|mimes:jpeg,png,jpg|max:2048' ]);
            $filename = $this->artworkService->uploadFile($validatedData);
            return response()->json(['message' => 'Success', 'filename' => $filename], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], $e->getCode());
        }
    }

    /**
     * @param int $id
     * @return Application|ResponseFactory|\Illuminate\Foundation\Application|JsonResponse|Response
     */
    public function download(int $id)
    {
        try {
            $image = $this->artworkService->getImage($id);
            return response($image['image'])->header('Content-Type', $image['contentType']);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], $e->getCode());
        }
    }
}
