<?php

namespace App\Services;

use App\Models\Sale;
use App\Models\User;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

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
        $sale->price = $data['price'];
        $sale->quantity = $data['quantity'];
        $sale->save();
    }
}
