<?php

test('the landing page links to the configured WhatsApp number', function () {
    config()->set('whatsapp-agent.number', '917977921376');

    $response = $this->get('/');

    $response->assertSee('https://wa.me/917977921376', false);
});

test('the WhatsApp number defaults to the bot contact when no env override is set', function () {
    expect(config('whatsapp-agent.number'))->toBe('917977921376');
});
