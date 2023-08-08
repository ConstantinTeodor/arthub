<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property int $artwork_id
 * @property int $listed_by
 * @property float $price
 * @property int $quantity
 */
class Sale extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'artowrk_sales';

    protected $primaryKey = 'id';

    public function artwork(): BelongsTo
    {
        return $this->belongsTo(Artwork::class, 'artwork_id', 'id');
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'listed_by', 'id');
    }
}
