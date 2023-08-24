<?php

namespace App\Console\Commands;

use App\Models\Auction;
use App\Models\ClientAuction;
use App\Models\ClientOrder;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CheckAuctionStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-auction-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $auctions = Auction::where('end_date', '<', now())
            ->where('winner_id', '=', null)
            ->get();

        if (empty($auctions)) return;

        foreach ($auctions as $auction) {
            $client = ClientAuction::where('auction_id', '=', $auction->id)
                ->orderBy('bid', 'desc')
                ->first();

            if (!$client) continue;
            $auction->winner_id = $client->client_id;
            $auction->save();

            $order = new ClientOrder();
            $order->client_id = $client->client_id;
            $order->ordered_via = 'auction';
            $order->final_amount = $client->bid;
            $order->payment = 'Credit card';
            $order->address = 'To be communicated';
            $order->save();

            $order->items()->attach($auction->artwork_id, [
                'quantity' => 1,
                'total_amount' => $client->bid
            ]);
            $order->save();
        }
    }
}
