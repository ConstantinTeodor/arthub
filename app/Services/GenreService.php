<?php

namespace App\Services;

use App\Models\Genre;
use Illuminate\Database\Eloquent\Collection;

class GenreService
{
    public function __construct() {}

    /**
     * @return Collection
     */
    public function getGenres(): Collection
    {
        return Genre::all();
    }
}
