<?php

namespace App\Services;

use App\Models\Auction;
use App\Models\User;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

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
        $auction->start_bid = $data['start_bid'];
        $auction->save();
    }
}
