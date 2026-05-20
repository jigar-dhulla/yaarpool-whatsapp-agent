<?php

declare(strict_types=1);

use App\Ai\Agents\YaarpoolAgent;

function loadAgentsConfig(array $env): array
{
    foreach ($env as $key => $value) {
        if ($value === null) {
            unset($_ENV[$key], $_SERVER[$key]);
            putenv($key);
        } else {
            $_ENV[$key] = $value;
            $_SERVER[$key] = $value;
            putenv("{$key}={$value}");
        }
    }

    return (require base_path('config/whatsapp-agent.php'))['agents'][0];
}

afterEach(function () {
    foreach (['YAARPOOL_TRIGGERS', 'YAARPOOL_CHATS', 'YAARPOOL_GROUPS'] as $key) {
        unset($_ENV[$key], $_SERVER[$key]);
        putenv($key);
    }
});

it('returns empty arrays when env vars are unset', function () {
    $agent = loadAgentsConfig([
        'YAARPOOL_TRIGGERS' => null,
        'YAARPOOL_CHATS' => null,
        'YAARPOOL_GROUPS' => null,
    ]);

    expect($agent['agent'])->toBe(YaarpoolAgent::class)
        ->and($agent['triggers'])->toBe([])
        ->and($agent['chats'])->toBe([])
        ->and($agent['groups'])->toBe([]);
});

it('returns empty arrays for empty strings', function () {
    $agent = loadAgentsConfig([
        'YAARPOOL_TRIGGERS' => '',
        'YAARPOOL_CHATS' => '',
        'YAARPOOL_GROUPS' => '',
    ]);

    expect($agent['triggers'])->toBe([])
        ->and($agent['chats'])->toBe([])
        ->and($agent['groups'])->toBe([]);
});

it('parses CSV values, trims whitespace, and drops blank entries', function () {
    $agent = loadAgentsConfig([
        'YAARPOOL_TRIGGERS' => '@123, @456 ,  ,  ',
        'YAARPOOL_CHATS' => '111@s.whatsapp.net,222@s.whatsapp.net',
        'YAARPOOL_GROUPS' => '  120363409213306573@g.us ,, 999@g.us',
    ]);

    expect($agent['triggers'])->toBe(['@123', '@456'])
        ->and($agent['chats'])->toBe(['111@s.whatsapp.net', '222@s.whatsapp.net'])
        ->and($agent['groups'])->toBe(['120363409213306573@g.us', '999@g.us']);
});

it('parses a single value without commas', function () {
    $agent = loadAgentsConfig([
        'YAARPOOL_GROUPS' => '120363409213306573@g.us',
    ]);

    expect($agent['groups'])->toBe(['120363409213306573@g.us']);
});
