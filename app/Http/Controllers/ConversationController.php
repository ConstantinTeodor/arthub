<?php

namespace App\Http\Controllers;

use App\Http\Resources\Conversation\ConversationIndexResource;
use App\Services\ConversationService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class ConversationController extends Controller
{
    public function __construct(
        protected ConversationService $conversationService
    ) {}

    /**
     * @return ConversationIndexResource|JsonResponse
     */
    public function index(): ConversationIndexResource|JsonResponse
    {
        try {
            $conversations = $this->conversationService->getConversations();
            return new ConversationIndexResource($conversations);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], $e->getCode());
        }
    }

    /**
     * @param int $conversationId
     * @return JsonResponse
     */
    public function update(int $conversationId): JsonResponse
    {
        try {
            $this->conversationService->updateConversation($conversationId);
            return response()->json(['message' => 'Conversation updated'], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], $e->getCode());
        }
    }
}
