<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\User;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});


Broadcast::channel('chat.{receiver}', function (User $user, $receiver) {

    #check if user is same as receiver

    return (int) $user->id === (int) $receiver;
});
