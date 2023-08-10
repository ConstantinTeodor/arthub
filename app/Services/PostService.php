<?php

namespace App\Services;

use App\Models\Post;
use App\Models\User;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class PostService
{
    public function __construct() {}

    /**
     * @param array $properties
     * @return void
     * @throws Exception
     */
    public function addPost(array $properties): void
    {
        if (Auth::check()) {
            $user = User::findOrFail(Auth::user()->id);
        } else {
            throw new Exception('Unauthorized', Response::HTTP_UNAUTHORIZED);
        }

        $client = $user->client;

        if (empty($client)) {
            throw new Exception('Client not found', Response::HTTP_NOT_FOUND);
        }

        $post = new Post();
        $post->client()->associate($client);
        $post->artwork_id = $properties['artwork_id'];
        $post->description = $properties['description'];
        $post->image_url = $properties['image_url'];
        $post->save();
    }
}
