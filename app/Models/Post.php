<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property int $client_id
 * @property int $artwork_id
 * @property string $description
 * @property string $image_url
 */
class Post extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'posts';

    protected $primaryKey = 'id';

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'client_id', 'id');
    }

    public function artwork(): BelongsTo
    {
        return $this->belongsTo(Artwork::class, 'artwork_id', 'id');
    }

    public function likes(): BelongsToMany
    {
        return $this->belongsToMany(PostLike::class, 'post_likes','post_id', 'id');
    }

    public function comments(): BelongsToMany
    {
        return $this->belongsToMany(PostComment::class, 'post_comments', 'post_id', 'id');
    }
}
