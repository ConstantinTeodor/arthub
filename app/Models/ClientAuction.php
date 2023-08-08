<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $client_id
 * @property int $auction_id
 * @property float $available_sum
 * @property float $bid
 */
class ClientAuction extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'client_auction';

    protected $primaryKey = ['client_id', 'auction_id'];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'client_id', 'id');
    }

    public function auction(): BelongsTo
    {
        return $this->belongsTo(Auction::class, 'auction_id', 'id');
    }
}
