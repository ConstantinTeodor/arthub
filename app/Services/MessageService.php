<?php

namespace App\Services;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class MessageService
{
    public function __construct() {}

    /**
     * @param int $conversationId
     * @return mixed
     * @throws Exception
     */
    public function getMessages(int $conversationId): mixed
    {
        if (Auth::check()) {
            $user = User::findOrFail(Auth::user()->id);
        } else {
            throw new Exception('Unauthorized', Response::HTTP_UNAUTHORIZED);
        }

        $client = $user->client;

        if (empty($client)) {
            throw new Exception('Client not found', Response::HTTP_NOT_FOUND);
        }

        $messages = Message::where('conversation_id', '=', $conversationId)
            ->orderBy('created_at', 'asc')
            ->get();

        foreach ($messages as $message) {
            if ($message->sender_id === $client->id) {
                $message->isFromMe = true;
            } else {
                $message->isFromMe = false;
            }
        }

        $conversation = Conversation::findOrFail($conversationId);
        $conversation->unread_messages = 0;
        $conversation->save();

        return $messages;
    }

    /**
     * @param array $data
     * @return void
     * @throws Exception
     */
    public function storeMessage(array $data): void
    {
        if (Auth::check()) {
            $user = User::findOrFail(Auth::user()->id);
        } else {
            throw new Exception('Unauthorized', Response::HTTP_UNAUTHORIZED);
        }

        $client = $user->client;

        if (empty($client)) {
            throw new Exception('Client not found', Response::HTTP_NOT_FOUND);
        }

        $conversation = Conversation::findOrFail($data['conversation_id']);

        $message = new Message();
        $message->conversation()->associate($conversation);
        $message->sender()->associate($client);

        $conversation->unread_messages += 1;
        $conversation->save();

        $message->message = $data['message'];
        $message->save();
    }
}
