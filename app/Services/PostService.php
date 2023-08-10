<?php

namespace App\Services;

use App\Models\Post;
use App\Models\PostComment;
use App\Models\PostLike;
use App\Models\User;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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

    /**
     * @param int $post_id
     * @return mixed
     */
    public function getPostData(int $post_id): mixed
    {
        $post = Post::findOrFail($post_id);

        $post->load('artwork');
        $post->load('client');
        $post->user = $post->client->user;

        if (!empty(PostComment::where('post_id', '=', $post_id)->first())) {
            $comments = PostComment::where('post_id', '=', $post_id)->get();
            $postComments = [];
            foreach ($comments as $comment) {
                $comment->load('client');
                $comment->user = $comment->client->user;
                $postComments[] = $comment;
            }
            $post->comments = $postComments;
        } else {
            $post->comments = [];
        }

        if (!empty(PostLike::where('post_id', '=', $post_id)->first())) {
            $post->likes = PostLike::where('post_id', '=', $post_id)->get();
        } else {
            $post->likes = [];
        }

        return $post;
    }

    /**
     * @param array $data
     * @return void
     * @throws Exception
     */
    public function likePost(array $data): void
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

        $post = Post::findOrFail((int)$data['post_id']);

        $postLike = PostLike::where('client_id', '=', $client->id)
            ->where('post_id', '=', $post->id)
            ->first();

        if (!empty($postLike)) {
            Post::find($post->id)->likes()->detach($postLike->id);
        } else {
            $like = new PostLike();
            $like->client()->associate($client);
            $like->post()->associate($post);
            $like->save();
        }
    }

    /**
     * @param int $post_id
     * @return bool
     * @throws Exception
     */
    public function liked(int $post_id): bool
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

        $post = Post::findOrFail($post_id);

        $postLike = PostLike::where('client_id', '=', $client->id)
            ->where('post_id', '=', $post->id)
            ->first();

        if (!empty($postLike)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param array $data
     * @return void
     * @throws Exception
     */
    public function addComment(array $data): void
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

        $post = Post::findOrFail((int)$data['post_id']);

        $comment = new PostComment();
        $comment->client()->associate($client);
        $comment->post()->associate($post);
        $comment->comment = $data['comment'];
        $comment->save();
    }
}
