<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $client_id
 * @property int $comment_id
 */
class PostCommentLike extends Model
{
    use HasFactory;

    protected $table = 'post_comment_likes';

    protected $primaryKey = ['client_id', 'comment_id'];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'client_id', 'id');
    }

    public function comment(): BelongsTo
    {
        return $this->belongsTo(PostComment::class, 'comment_id', 'id');
    }
}
