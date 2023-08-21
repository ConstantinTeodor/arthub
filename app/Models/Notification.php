<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $client_id
 * @property string $status
 * @property string $title
 * @property string $message
 * @property int $from_id
 */
class Notification extends Model
{
    use HasFactory;

    protected $table = 'notifications';

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'client_id', 'id');
    }

    public function from(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'from_id', 'id');
    }
}
