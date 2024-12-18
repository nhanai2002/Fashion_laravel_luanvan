<?php

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('send-all', function ($user) {
    return Auth::check();
});


Broadcast::channel('user-channel-{userId}', function ($user, $userId) {
    return (int)$user->id == (int)$userId;
});

Broadcast::channel('conversation.{conversationId}', function ($user, $conversationId) {
    return Auth::check();
});
