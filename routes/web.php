<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $number = config('whatsapp-agent.number');

    return view('welcome', [
        'whatsappInviteUrl' => $number ? 'https://wa.me/'.$number : null,
    ]);
});
