<?php

namespace App\Http\Controllers;

use App\Services\TypeService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class TypeController extends Controller
{
    public function __construct(
        protected TypeService $typeService,
    ) {}

    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            $types = $this->typeService->getTypes();
            return response()->json([ 'types' => $types ], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json([ 'message' => $e->getMessage() ], $e->getCode());
        }
    }
}
