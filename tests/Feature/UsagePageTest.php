<?php

test('the usage page is public and returns a successful response', function () {
    $response = $this->get('/usage');

    $response->assertStatus(200);
});

test('the usage page shows how to use the bot with examples', function () {
    $response = $this->get('/usage');

    $response->assertSee('How to use');
    $response->assertSee('Offer a ride');
    $response->assertSee('Ask for a lift');
    $response->assertSee('Join a ride');
    $response->assertSee('Cancel your ride');
    $response->assertSee('Driving from Andheri to BKC tomorrow at 9am, 2 seats free 🚙');
});

test('the landing page links to the usage guide', function () {
    $response = $this->get('/');

    $response->assertSee(route('usage'), false);
});
