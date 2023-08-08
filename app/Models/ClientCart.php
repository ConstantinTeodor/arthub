<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $client_id
 * @property int $artwork_id
 * @property int $quantity
 * @property float $total_amount
 */
class ClientCart extends Model
{
    use HasFactory;

    protected $table = 'client_cart_items';

    protected $primaryKey = ['client_id', 'artwork_id'];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'client_id', 'id');
    }

    public function artworks(): HasMany
    {
        return $this->hasMany(Artwork::class, 'artwork_id', 'id');
    }
}
