<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property int $user_id
 * @property string $first_name
 * @property string $last_name
 * @property string $middle_name
 * @property DateTime $date_of_birth
 */
class Client extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'clients';

    protected $primaryKey = 'id';

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class, 'client_id', 'id');
    }

    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class, 'listed_by', 'id');
    }

    public function auctions(): HasMany
    {
        return $this->hasMany(Auction::class, 'creator_id', 'id');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(ClientOrder::class, 'client_id', 'id');
    }
}
