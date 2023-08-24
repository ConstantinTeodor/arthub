<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $conversation_id
 * @property int $sender_id
 * @property string $message
 */
class Message extends Model
{
    use HasFactory;

    protected $table = 'messages';

    public function conversation(): BelongsTo
    {
        return $this->belongsTo(Conversation::class, 'conversation_id', 'id');
    }

    public function sender(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'sender_id', 'id');
    }
}
