<?php

namespace App\Http\Controllers;

use App\Services\GenreService;
use Exception;
use Illuminate\Http\Response;

class GenreController extends Controller
{
    public function __construct(
        protected GenreService $genreService,
    ) {}

    public function index()
    {
        try {
            $genres = $this->genreService->getGenres();
            return response()->json([ 'genres' => $genres ], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json([ 'message' => $e->getMessage() ], $e->getCode());
        }
    }
}
