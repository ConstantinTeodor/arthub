<?php

namespace App\Http\Controllers;

use App\Http\Requests\Notification\NotificationReadRequest;
use App\Http\Resources\Notification\NotificationIndexResource;
use App\Services\NotificationService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class NotificationController extends Controller
{
    public function __construct(
        protected NotificationService $notificationService
    ) {}

    /**
     * @return NotificationIndexResource|JsonResponse
     */
    public function index(): NotificationIndexResource|JsonResponse
    {
        try {
            $notifications = $this->notificationService->getNotifications();
            return new NotificationIndexResource($notifications);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], $e->getCode());
        }
    }

    /**
     * @param NotificationReadRequest $request
     * @return JsonResponse
     */
    public function markAsRead(NotificationReadRequest $request): JsonResponse
    {
        try {
            $validatedData = $request->validated();
            $this->notificationService->markAsRead($validatedData);
            return response()->json(['message' => 'Notification marked as read'], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], $e->getCode());
        }
    }

    /**
     * @return JsonResponse
     */
    public function readAll(): JsonResponse
    {
        try {
            $this->notificationService->readAll();
            return response()->json(['message' => 'All notifications marked as read'], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], $e->getCode());
        }
    }
}
