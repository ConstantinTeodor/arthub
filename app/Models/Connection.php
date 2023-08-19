<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $requester_id
 * @property int $receiver_id
 * @property string $status
 */
class Connection extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'connections';

    public function requester(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'requester_id', 'id');
    }

    public function receiver(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'receiver_id', 'id');
    }
}
