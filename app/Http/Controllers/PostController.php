<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostStoreRequest;
use App\Services\PostService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class PostController extends Controller
{
    public function __construct(
        protected PostService $postService,
    ) {}

    /**
     * @param PostStoreRequest $request
     * @return JsonResponse
     */
    public function store(PostStoreRequest $request): JsonResponse
    {
        try {
            $validatedData = $request->validated();
            $this->postService->addPost($validatedData);
            return response()->json([ 'message' => 'Success' ], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json([ 'message' => $e->getMessage() ], $e->getCode());
        }
    }
}
