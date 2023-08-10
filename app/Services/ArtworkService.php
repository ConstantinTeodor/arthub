<?php

namespace App\Services;

use App\Models\Artwork;
use App\Models\Genre;
use App\Models\Type;
use Illuminate\Support\Facades\Log;

class ArtworkService
{
    public function __construct() {}

    /**
     * @param array $data
     * @return int
     */
    public function addArtwork(array $data): int
    {
        $artwork = new Artwork();
        $artwork->title = $data['title'];
        $artwork->artist = $data['artist'];

        $artwork->save();

        $type = Type::where('name', '=', $data['type'])->first();
        $artwork->types()->attach($type);
        $genre = Genre::where('name', '=', $data['genre'])->first();
        $artwork->genres()->attach($genre);

        $artwork->save();

        return $artwork->id;
    }

    /**
     * @param array $data
     * @return string
     */
    public function uploadFile(array $data): string
    {
        $file = $data['file'];
        $filename = time() . '_' . $file->getClientOriginalName();
        $file->move(public_path('uploads'), $filename);

        return $filename;
    }
}
