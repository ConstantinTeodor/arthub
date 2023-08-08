<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property int $creator_id
 * @property int $winner_id
 * @property int $artwork_id
 * @property string $name
 * @property DateTime $start_date
 * @property DateTime $end_date
 * @property float $start_bid
 * @property float $current_bid
 */
class Auction extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'auctions';

    protected $primaryKey = 'id';

    public function creator(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'creator_id', 'id');
    }

    public function winner(): HasOne
    {
        return $this->hasOne(Client::class, 'winner_id', 'id');
    }

    public function artwork(): HasOne
    {
        return $this->hasOne(Artwork::class, 'artwork_id', 'id');
    }
}
