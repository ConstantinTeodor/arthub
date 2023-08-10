<?php

namespace App\Services;

use App\Models\Artwork;
use App\Models\Genre;
use App\Models\Post;
use App\Models\Type;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

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

    /**
     * @param int $post_id
     * @return array
     * @throws Exception
     */
    public function getImage(int $post_id): array
    {
        $post = Post::findOrFail($post_id);
        $imagePath = public_path('uploads/' . $post->image_url);

        if (file_exists($imagePath)) {
            $fileContents = file_get_contents($imagePath);
            $contentType = mime_content_type($imagePath);

            return [
                'image' => $fileContents,
                'contentType' => $contentType
            ];
        } else {
            throw new Exception('Not found', Response::HTTP_NOT_FOUND);
        }
    }
}
