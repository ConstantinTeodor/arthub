<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property string $title
 * @property string $artist
 */
class Artwork extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'artworks';

    protected $primaryKey = 'id';

    public function genres(): BelongsToMany
    {
        return $this->belongsToMany(Genre::class, 'artwork_genre', 'artwork_id', 'genre_id');
    }

    public function types(): BelongsToMany
    {
        return $this->belongsToMany(Type::class, 'artwork_type', 'artwork_id', 'type_id');
    }
}
