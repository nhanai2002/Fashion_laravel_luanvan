<?php

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

// Broadcast::channel('FashionCore.Models.User.{id}', function ($user, $id) {
//     return (int) $user->id === (int) $id;
// });


Broadcast::channel('admin-channel', function ($user) {
    return $user->role_id === 1;
});

Broadcast::channel('send-all', function ($user) {
    return Auth::check();
});

Broadcast::channel('user-channel-{userId}', function ($user, $userId) {
    return $user->id == $userId;
});
