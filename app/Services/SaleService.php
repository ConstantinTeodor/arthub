<?php

namespace App\Services;

use App\Models\Artwork;
use App\Models\Client;
use App\Models\ClientCart;
use App\Models\Genre;
use App\Models\Post;
use App\Models\Sale;
use App\Models\Type;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SaleService
{
    public function __construct() {}

    /**
     * @param array $data
     * @return void
     * @throws Exception
     */
    public function addSale(array $data): void
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

        $sale = new Sale();
        $sale->client()->associate($client);
        $sale->artwork_id = $data['artwork_id'];
        $sale->price = (float)$data['price'];
        $sale->quantity = (int)$data['quantity'];
        $sale->save();
    }

    /**
     * @return mixed
     * @throws Exception
     */
    public function getLatest(): mixed
    {
        if (Auth::check()) {
            $user = User::findOrFail(Auth::user()->id);
        } else {
            throw new Exception('Unauthorized', Response::HTTP_UNAUTHORIZED);
        }

        $latest = Sale::orderBy('created_at', 'desc')->take(10)->get();

        foreach ($latest as $item) {
            $item->load('artwork');
            $post = Post::where('artwork_id', $item->artwork_id)->first();
            $item->image = $post->id;
            $item->load('client');
        }

        return $latest;
    }

    /**
     * @param array $data
     * @return Builder[]|Collection
     * @throws Exception
     */
    public function getFiltered(array $data): Collection|array
    {
        if (Auth::check()) {
            $user = User::findOrFail(Auth::user()->id);
        } else {
            throw new Exception('Unauthorized', Response::HTTP_UNAUTHORIZED);
        }

        $queryBuilder = Artwork::query();

        if (!empty($data['genres'])) {
            $genreIds = [];
            foreach ($data['genres'] as $genre) {
                $genreIds[] = Genre::where('name', '=', $genre)->first()->id;
            }
            $queryBuilder->whereHas('genres', function ($query) use ($genreIds) {
                $query->whereIn('id', $genreIds);
            })->get();
        }

        if (!empty($data['types'])) {
            $typeIds = [];
            foreach ($data['types'] as $type) {
                $typeIds[] = Type::where('name', '=', $type)->first()->id;
           }
            $queryBuilder->whereHas('genres', function ($query) use ($typeIds) {
                $query->whereIn('id', $typeIds);
            })->get();
        }

        if (!empty($data['artists'])) {
            $queryBuilder->whereIn('artist', $data['artists'])->get();
        }

        $queryBuilder->join('artwork_sales', 'artworks.id', '=', 'artwork_sales.artwork_id');

        $result = $queryBuilder->get();

        if (empty($result)) {
            $queryBuilderAll = Artwork::query();
            $queryBuilderAll->join('artwork_sales', 'artworks.id', '=', 'artwork_sales.artwork_id');
            $result = $queryBuilderAll->get();
        }

        foreach ($result as $artwork) {
            $post = Post::where('artwork_id', $artwork->artwork_id)->first();
            $artwork->image = $post->id;
            $client = Client::where('id', $artwork->listed_by)->first();
            $artwork->client = $client;
        }

        return $result;
    }
}
