<?php

namespace App\Http\Controllers;

use App\Http\Requests\Post\PostCommentEditRequest;
use App\Http\Requests\Post\PostStoreCommentRequest;
use App\Http\Requests\Post\PostStoreRequest;
use App\Http\Requests\Post\PostUpdateRequest;
use App\Http\Resources\Post\PostShowResource;
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

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function likeComment(Request $request): JsonResponse
    {
        try {
            $validatedData = $request->validate([ 'comment_id' => 'required|integer' ]);
            $this->postService->likeComment($validatedData);
            return response()->json([ 'message' => 'Success' ], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json([ 'message' => $e->getMessage() ], $e->getCode());
        }
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function commentLiked(int $id): JsonResponse
    {
        try {
            $liked = $this->postService->commentLiked($id);
            return response()->json([ 'likedComment' => $liked ], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json([ 'message' => $e->getMessage() ], $e->getCode());
        }
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function deleteComment(int $id): JsonResponse
    {
        try {
            $this->postService->deleteComment($id);
            return response()->json([ 'message' => 'Success' ], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json([ 'message' => $e->getMessage() ], $e->getCode());
        }
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function deletePost(int $id): JsonResponse
    {
        try {
            $this->postService->deletePost($id);
            return response()->json([ 'message' => 'Success' ], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json([ 'message' => $e->getMessage() ], $e->getCode());
        }
    }

    /**
     * @param PostCommentEditRequest $request
     * @return JsonResponse
     */
    public function editComment(PostCommentEditRequest $request): JsonResponse
    {
        try {
            $validatedData = $request->validated();
            $this->postService->editComment($validatedData);
            return response()->json([ 'message' => 'Success' ], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json([ 'message' => $e->getMessage() ], $e->getCode());
        }
    }

    /**
     * @param PostUpdateRequest $request
     * @return JsonResponse
     */
    public function update(PostUpdateRequest $request): JsonResponse
    {
        try {
            $validatedData = $request->validated();
            $this->postService->updatePost($validatedData);
            return response()->json([ 'message' => 'Success' ], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json([ 'message' => $e->getMessage() ], $e->getCode());
        }
    }

    /**
     * @return JsonResponse
     */
    public function feed(): JsonResponse
    {
        try {
            $feed = $this->postService->feed();
            return response()->json([ 'feed' => $feed ], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json([ 'message' => $e->getMessage() ], $e->getCode());
        }
    }
}
