<?php

namespace App\Services;

use App\Models\User;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class ConversationService
{
    public function __construct() {}

    /**
     * @return mixed
     * @throws Exception
     */
    public function getConversations(): mixed
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

        $conversations = $client->conversations;

        try {
            foreach ($conversations as $conversation) {
                $conversation->load('clients');

                $conversation->load(['messages' => function ($query) {
                    $query->orderBy('created_at', 'desc');
                }]);

                $conversation->myId = $client->id;
            }

            $conversations = $conversations->toArray();

            usort($conversations, function ($a, $b) {
                $aLastMessageTimestamp = $a['messages'][0]['created_at'] ?? '1970-01-01T00:00:00.000000Z';
                $bLastMessageTimestamp = $b['messages'][0]['created_at'] ?? '1970-01-01T00:00:00.000000Z';

                return $bLastMessageTimestamp <=> $aLastMessageTimestamp;
            });

        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode());
        }

        return $conversations;
    }

    /**
     * @param int $conversationId
     * @return void
     * @throws Exception
     */
    public function updateConversation(int $conversationId): void
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

        $conversation = $client->conversations()->where('conversation_id', '=', $conversationId)->first();
        $conversation->unread_messages = 0;
        $conversation->save();
    }
}
