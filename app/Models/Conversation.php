<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string name
 * @property int unread_messages
 */
class Conversation extends Model
{
    use HasFactory;

    protected $table = 'conversations';

    public function clients(): BelongsToMany
    {
        return $this->belongsToMany(Client::class, 'conversation_client', 'conversation_id', 'client_id');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class, 'conversation_id', 'id');
    }
}
