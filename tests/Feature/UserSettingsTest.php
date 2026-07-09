<?php

declare(strict_types=1);

use App\Ai\Tools\RideCreateTool;
use App\Ai\Tools\RideRequestTool;
use App\Ai\Tools\UserSettingsTool;
use App\Models\GroupSetting;
use App\Models\Ride;
use App\Models\UserSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\JsonSchema\JsonSchemaTypeFactory;
use Laravel\Ai\Tools\Request;

uses(RefreshDatabase::class);

it('saves the user\'s usual route through the tool', function () {
    $senderJid = '919999999999@s.whatsapp.net';

    $reply = (new UserSettingsTool(senderJid: $senderJid))->handle(new Request([
        'from' => 'Wakad',
        'to' => 'Hinjewadi',
    ]));

    expect(UserSetting::forSender($senderJid))
        ->default_from_location->toBe('Wakad')
        ->default_to_location->toBe('Hinjewadi')
        ->and((string) $reply)->toContain('Wakad → Hinjewadi');
});

it('saves only a usual starting location', function () {
    $senderJid = '919999999999@s.whatsapp.net';

    $reply = (new UserSettingsTool(senderJid: $senderJid))->handle(new Request([
        'from' => 'Wakad',
    ]));

    expect(UserSetting::forSender($senderJid))
        ->default_from_location->toBe('Wakad')
        ->default_to_location->toBeNull()
        ->and((string) $reply)->toContain('starting from Wakad');
});

it('updates only the destination and keeps the saved origin', function () {
    $senderJid = '919999999999@s.whatsapp.net';

    UserSetting::factory()->create([
        'sender_jid' => $senderJid,
        'default_from_location' => 'Wakad',
        'default_to_location' => 'Hinjewadi',
    ]);

    (new UserSettingsTool(senderJid: $senderJid))->handle(new Request([
        'to' => 'Baner',
    ]));

    expect(UserSetting::forSender($senderJid))
        ->default_from_location->toBe('Wakad')
        ->default_to_location->toBe('Baner');
});

it('asks for a starting location when only a destination is given and nothing is saved yet', function () {
    $reply = (new UserSettingsTool(senderJid: '919999999999@s.whatsapp.net'))->handle(new Request([
        'to' => 'Hinjewadi',
    ]));

    expect(UserSetting::count())->toBe(0)
        ->and((string) $reply)->toContain('usual starting location');
});

it('shows the saved defaults when called with no fields', function () {
    $senderJid = '919999999999@s.whatsapp.net';

    UserSetting::factory()->create([
        'sender_jid' => $senderJid,
        'default_from_location' => 'Wakad',
        'default_to_location' => 'Hinjewadi',
    ]);

    $reply = (new UserSettingsTool(senderJid: $senderJid))->handle(new Request([]));

    expect((string) $reply)->toContain('Wakad → Hinjewadi');
});

it('explains when no defaults are saved yet', function () {
    $reply = (new UserSettingsTool(senderJid: '919999999999@s.whatsapp.net'))->handle(new Request([]));

    expect((string) $reply)->toContain('no personal defaults');
});

it('refuses to manage defaults when the sender JID is not known', function () {
    $reply = (new UserSettingsTool)->handle(new Request([
        'from' => 'Wakad',
    ]));

    expect(UserSetting::count())->toBe(0)
        ->and((string) $reply)->toContain('cannot verify');
});

it('scopes defaults to the sender, not the whole chat', function () {
    UserSetting::factory()->create([
        'sender_jid' => '919999999999@s.whatsapp.net',
        'default_from_location' => 'Wakad',
    ]);

    $reply = (new UserSettingsTool(senderJid: '911111111111@s.whatsapp.net'))->handle(new Request([]));

    expect((string) $reply)->toContain('no personal defaults');
});

it('fills a ride request from the user\'s saved route', function () {
    $senderJid = '919999999999@s.whatsapp.net';

    UserSetting::factory()->create([
        'sender_jid' => $senderJid,
        'default_from_location' => 'Wakad',
        'default_to_location' => 'Hinjewadi',
    ]);

    (new RideRequestTool(chatJid: '120363409213306573@g.us', senderJid: $senderJid))->handle(new Request([
        'when_text' => 'tomorrow 8am',
        'departs_at' => '2026-07-10T08:00:00',
    ]));

    $ride = Ride::sole();
    expect($ride->from_location)->toBe('Wakad')
        ->and($ride->to_location)->toBe('Hinjewadi');
});

it('prefers the user default over the group default on a ride offer', function () {
    $chatJid = '120363409213306573@g.us';
    $senderJid = '918888888888@s.whatsapp.net';

    GroupSetting::factory()->create([
        'chat_jid' => $chatJid,
        'default_from_location' => 'Sector 21 gate',
        'default_to_location' => 'Tech Park',
    ]);

    UserSetting::factory()->create([
        'sender_jid' => $senderJid,
        'default_from_location' => 'Wakad',
    ]);

    (new RideCreateTool(chatJid: $chatJid, senderJid: $senderJid))->handle(new Request([
        'when_text' => 'today 6pm',
        'departs_at' => '2026-07-09T18:00:00',
        'seats' => 3,
    ]));

    $ride = Ride::sole();
    expect($ride->from_location)->toBe('Wakad')
        ->and($ride->to_location)->toBe('Tech Park');
});

it('prefers an explicit location over the user default', function () {
    $senderJid = '919999999999@s.whatsapp.net';

    UserSetting::factory()->create([
        'sender_jid' => $senderJid,
        'default_from_location' => 'Wakad',
        'default_to_location' => 'Hinjewadi',
    ]);

    (new RideRequestTool(chatJid: '120363409213306573@g.us', senderJid: $senderJid))->handle(new Request([
        'from' => 'Main Street',
        'when_text' => 'tomorrow 8am',
        'departs_at' => '2026-07-10T08:00:00',
    ]));

    $ride = Ride::sole();
    expect($ride->from_location)->toBe('Main Street')
        ->and($ride->to_location)->toBe('Hinjewadi');
});

it('does not apply another sender\'s defaults to a ride', function () {
    UserSetting::factory()->create([
        'sender_jid' => '919999999999@s.whatsapp.net',
        'default_from_location' => 'Wakad',
    ]);

    $reply = (new RideRequestTool(chatJid: '120363409213306573@g.us', senderJid: '911111111111@s.whatsapp.net'))->handle(new Request([
        'to' => 'BKC',
        'when_text' => 'tomorrow 8am',
        'departs_at' => '2026-07-10T08:00:00',
    ]));

    expect(Ride::count())->toBe(0)
        ->and((string) $reply)->toContain('default pickup location');
});

it('exposes the expected name and schema keys to the agent', function () {
    $tool = new UserSettingsTool;

    expect($tool->name())->toBe('user_settings')
        ->and(array_keys($tool->schema(new JsonSchemaTypeFactory)))->toBe(['from', 'to']);
});
