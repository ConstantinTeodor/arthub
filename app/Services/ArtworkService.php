<?php

namespace App\Services;

use App\Models\Artwork;
use App\Models\Genre;
use App\Models\Post;
use App\Models\Type;
use Exception;
use Illuminate\Http\Response;

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

        foreach ($data['type'] as $type) {
            $type = Type::where('name', '=', $type)->first();
            $artwork->types()->attach($type);
        }

        foreach ($data['genre'] as $genre) {
            $genre = Genre::where('name', '=', $genre)->first();
            $artwork->genres()->attach($genre);
        }

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

    /**
     * @return mixed
     */
    public function getArtists(): mixed
    {
        return Artwork::select('artist')->distinct()->get();
    }
}
