<?php

namespace App\Services;

use App\Models\Artwork;

class ArtworkService
{
    public function __construct() {}

    /**
     * @param array $data
     * @return void
     */
    public function addArtwork(array $data): void
    {
        $artwork = new Artwork();
        $artwork->title = $data['title'];
        $artwork->artist = $data['artist'];
        $artwork->save();
    }
}
