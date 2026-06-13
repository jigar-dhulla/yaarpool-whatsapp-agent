<?php

test('the application returns a successful response', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
});

test('the landing page shows the headline and WhatsApp call to action', function () {
    $response = $this->get('/');

    $response->assertSee('Share rides');
    $response->assertSee('The carpool that lives in your group chat.');
    $response->assertSee('No strangers. Just yaars.');
    $response->assertSee('Add to WhatsApp group');
    $response->assertSee('https://github.com/jigar-dhulla/yaarpool-whatsapp-agent', false);
});
