<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property int $client_order_id
 * @property int $artwork_id
 * @property int $quantity
 * @property float $total_amount
 */
class ClientOrderItem extends Model
{
    use HasFactory;

    protected $table = 'client_order_items';

    public function clientOrder(): BelongsTo
    {
        return $this->belongsTo(ClientOrder::class, 'client_order_id', 'id');
    }

    public function artwork(): HasOne
    {
        return $this->hasOne(Artwork::class, 'artwork_id', 'id');
    }
}
