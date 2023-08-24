<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property int $id
 * @property int $client_id
 * @property string $ordered_via
 * @property float $final_amount
 * @property string $payment
 * @property string $address
 */
class ClientOrder extends Model
{
    use HasFactory;

    protected $table = 'client_orders';

    protected $primaryKey = 'id';

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'client_id', 'id');
    }

    public function items(): BelongsToMany
    {
        return $this->belongsToMany(Artwork::class, 'client_order_items', 'client_order_id', 'artwork_id');
    }
}
