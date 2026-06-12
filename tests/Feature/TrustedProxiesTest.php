<?php

test('forwarded proto from a trusted proxy makes the request secure', function () {
    $this->call('GET', '/', server: [
        'REMOTE_ADDR' => '172.18.0.2',
        'HTTP_X_FORWARDED_PROTO' => 'https',
    ])->assertOk();

    expect(request()->isSecure())->toBeTrue()
        ->and(url('/'))->toStartWith('https://');
});

test('forwarded proto from an untrusted address is ignored', function () {
    $this->call('GET', '/', server: [
        'REMOTE_ADDR' => '203.0.113.10',
        'HTTP_X_FORWARDED_PROTO' => 'https',
    ])->assertOk();

    expect(request()->isSecure())->toBeFalse();
});
