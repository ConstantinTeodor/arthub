<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostStoreCommentRequest;
use App\Http\Requests\PostStoreRequest;
use App\Http\Resources\PostShowResource;
use App\Services\PostService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
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

    /**
     * @param int $id
     * @return PostShowResource|JsonResponse
     */
    public function show(int $id): JsonResponse|PostShowResource
    {
        try {
            $post = $this->postService->getPostData($id);
//            return response()->json([ 'post' => $post ], Response::HTTP_OK);
            return new PostShowResource($post);
        } catch (Exception $e) {
            return response()->json([ 'message' => $e->getMessage() ], $e->getCode());
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function like(Request $request): JsonResponse
    {
        try {
            $validatedData = $request->validate([ 'post_id' => 'required|integer' ]);
            $this->postService->likePost($validatedData);
            return response()->json([ 'message' => 'Success' ], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json([ 'message' => $e->getMessage() ], $e->getCode());
        }
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function liked(int $id): JsonResponse
    {
        try {
            $liked = $this->postService->liked($id);
            return response()->json([ 'likedPost' => $liked ], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json([ 'message' => $e->getMessage() ], $e->getCode());
        }
    }

    /**
     * @param PostStoreCommentRequest $request
     * @return JsonResponse
     */
    public function comment(PostStoreCommentRequest $request): JsonResponse
    {
        try {
            $validatedData = $request->validated();
            $this->postService->addComment($validatedData);
            return response()->json([ 'message' => 'Success' ], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json([ 'message' => $e->getMessage() ], $e->getCode());
        }
    }
}
