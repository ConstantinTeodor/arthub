<?php

namespace App\Observers;

use App\Models\Client;
use App\Models\Connection;
use App\Models\Notification;

class ConnectionStatusObserver
{
    /**
     * Handle the Connection "created" event.
     *
     * @param  Connection  $connectionRequest
     * @return void
     */
    public function created(Connection $connectionRequest): void
    {
        $from = Client::findOrfail($connectionRequest->requester_id);

        $notification = new Notification();
        $notification->client_id = $connectionRequest->receiver_id;
        $notification->title = "New Connection Request";
        $notification->message = "You have a new connection request from " . $from->first_name . ' ' . $from->last_name . '.';
        $notification->from_id = $connectionRequest->requester_id;
        $notification->save();
    }

    /**
     * Handle the Connection "updated" event.
     *
     * @param Connection $connectionRequest
     * @return void
     */
    public function updated(Connection $connectionRequest): void
    {
        if ($connectionRequest->isDirty('status')) {
            if ($connectionRequest->status == "accepted") {
                $from = Client::findOrfail($connectionRequest->receiver_id);

                $notification = new Notification();
                $notification->client_id = $connectionRequest->requester_id;
                $notification->title = "Connection Request Accepted";
                $notification->message = "Your connection request to " . $from->first_name . ' ' . $from->last_name . " has been accepted";
                $notification->from_id = $connectionRequest->receiver_id;
                $notification->save();
            }
        }
    }

}
