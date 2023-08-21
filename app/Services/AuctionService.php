<?php

namespace App\Services;

use App\Models\Artwork;
use App\Models\Auction;
use App\Models\Client;
use App\Models\ClientAuction;
use App\Models\Genre;
use App\Models\Post;
use App\Models\Type;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AuctionService
{
    public function __construct() {}

    /**
     * @param array $data
     * @return void
     * @throws Exception
     */
    public function addAuction(array $data): void
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

        $auction = new Auction();
        $auction->creator()->associate($client);
        $auction->artwork_id = $data['artwork_id'];
        $auction->name = $data['name'];
        $auction->start_date = Carbon::parse($data['start_date'])->format('Y-m-d H:i:s');
        $auction->end_date = Carbon::parse($data['end_date'])->format('Y-m-d H:i:s');
        $auction->start_bid = (float)$data['start_bid'];
        $auction->save();
    }

    /**
     * @param array $data
     * @return Builder[]|Collection
     * @throws Exception
     */
    public function getFilteredAuctions(array $data): Collection|array
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

        $queryBuilder->join('auctions', 'artworks.id', '=', 'auctions.artwork_id');

        $result = $queryBuilder->get();

        if (empty($result)) {
            $queryBuilderAll = Artwork::query();
            $queryBuilderAll->join('auctions', 'artworks.id', '=', 'auctions.artwork_id');
            $result = $queryBuilderAll->get();
        }

        foreach ($result as $artwork) {
            $post = Post::where('artwork_id', $artwork->artwork_id)->first();
            $artwork->image = $post->id;
            $client = Client::where('id', $artwork->creator_id)->first();
            $artwork->client = $client;
        }

        return $result;
    }

    /**
     * @param int $auctionId
     * @return mixed
     */
    public function getAuction(int $auctionId): mixed
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

        $auction = Auction::findOrFail($auctionId);
        $artwork = Artwork::findOrFail($auction->artwork_id);
        $post = Post::where('artwork_id', $artwork->id)->first();
        $clientAuction = ClientAuction::where('client_id', '=', $client->id)->where('auction_id', '=', $auction->id)->first();
        if (empty($clientAuction)) {
            $auction->participate = false;
        } else {
            $auction->participate = true;
            $auction->clientAuction = $clientAuction;
        }
        $auction->image = $post->id;
        $auction->artwork = $artwork;
        $auction->post = $post;

        return $auction;
    }

    /**
     * @param array $data
     * @return void
     * @throws Exception
     */
    public function participate(array $data): void
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

        $clientAuction = ClientAuction::where('client_id', '=', $client->id)->where('auction_id', '=', $data['auction_id'])->first();

        if (empty($clientAuction)) {
            $clientAuction = new ClientAuction();
            $clientAuction->client()->associate($client);
            $clientAuction->auction_id = $data['auction_id'];
            $clientAuction->available_sum = $data['available_sum'];
            $client->bid = 0;
            $clientAuction->save();
        } else {
            DB::table('client_auction')
                ->where('client_id', '=', $client->id)
                ->where('auction_id', '=', $data['auction_id'])
                ->delete();
        }
    }

    /**
     * @param array $data
     * @return void
     * @throws Exception
     */
    public function bid(array $data): void
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

        $clientAuction = ClientAuction::where('client_id', '=', $client->id)->where('auction_id', '=', $data['auction_id'])->first();

        if (empty($clientAuction)) {
            throw new Exception('Client not found', Response::HTTP_NOT_FOUND);
        }

        $auction = Auction::findOrFail($data['auction_id']);

        if ($data['bid'] > $clientAuction->available_sum) {
            throw new Exception('Not enough funds', Response::HTTP_BAD_REQUEST);
        }

        if ($auction->start_bid > $data['bid']) {
            throw new Exception('Bid is too low', Response::HTTP_BAD_REQUEST);
        }

        if ($auction->current_bid >= $data['bid']) {
            throw new Exception('Bid is too low', Response::HTTP_BAD_REQUEST);
        }

        DB::table('client_auction')
            ->where('client_id', '=', $client->id)
            ->where('auction_id', '=', $data['auction_id'])
            ->update(['bid' => $data['bid']]);

        $auction->current_bid = $data['bid'];
        $auction->save();
    }

    public function getAuctions()
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

        $clientAuctions = ClientAuction::where('client_id', '=', $client->id)->get();

        $auctions = [];

        foreach ($clientAuctions as $clientAuction) {
            $auction = Auction::findOrFail($clientAuction->auction_id);
            $artwork = Artwork::findOrFail($auction->artwork_id);
            $post = Post::where('artwork_id', $artwork->id)->first();
            $client = Client::where('id', $auction->creator_id)->first();
            $auction->image = $post->id;
            $auction->artwork = $artwork;
            $auction->post = $post;
            $auction->client = $client;
            $auctions[] = $auction;
        }

        return $auctions;
    }
}
