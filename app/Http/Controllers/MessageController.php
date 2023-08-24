<?php

namespace App\Http\Controllers;

use App\Http\Requests\Message\MessageStoreRequest;
use App\Http\Resources\Message\MessageShowResource;
use App\Services\MessageService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class MessageController extends Controller
{
    public function __construct(
        protected MessageService $messageService
    ) {}

    /**
     * @param int $conversationId
     * @return MessageShowResource|JsonResponse
     */
    public function show(int $conversationId): MessageShowResource|JsonResponse
    {
        try {
            $messages = $this->messageService->getMessages($conversationId);
            return new MessageShowResource($messages);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], $e->getCode());
        }
    }

    /**
     * @param MessageStoreRequest $request
     * @return JsonResponse
     */
    public function store(MessageStoreRequest $request): JsonResponse
    {
        try {
            $validatedData = $request->validated();
            $this->messageService->storeMessage($validatedData);
            return response()->json(['message' => 'Message sent'], Response::HTTP_CREATED);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], $e->getCode());
        }
    }
}
