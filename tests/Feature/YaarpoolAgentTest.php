<?php

declare(strict_types=1);

use App\Ai\Agents\YaarpoolAgent;

function setAgentConfig(array $overrides = []): void
{
    config(['whatsapp-agent.agents' => [
        array_merge([
            'agent' => YaarpoolAgent::class,
            'triggers' => [],
            'chats' => [],
            'groups' => [],
        ], $overrides),
    ]]);
}

it('injects the configured triggers into the instructions', function () {
    setAgentConfig(['triggers' => ['@yaarpool', '@123456789']]);

    $instructions = (string) (new YaarpoolAgent)->instructions();

    expect($instructions)->toContain('You as agent are mentioned/triggered by these triggers: @yaarpool, @123456789');
});

it('omits the triggers line when no triggers are configured', function () {
    setAgentConfig(['triggers' => []]);

    $instructions = (string) (new YaarpoolAgent)->instructions();

    expect($instructions)->not->toContain('You as agent are mentioned/triggered by these triggers');
});

it('only includes triggers for this agent class', function () {
    config(['whatsapp-agent.agents' => [
        ['agent' => 'App\\Ai\\Agents\\OtherAgent', 'triggers' => ['@other']],
        ['agent' => YaarpoolAgent::class, 'triggers' => ['@mine']],
    ]]);

    $instructions = (string) (new YaarpoolAgent)->instructions();

    expect($instructions)->toContain('triggers: @mine')
        ->and($instructions)->not->toContain('@other');
});

it('omits the triggers line when the agent is absent from config', function () {
    config(['whatsapp-agent.agents' => [
        ['agent' => 'App\\Ai\\Agents\\OtherAgent', 'triggers' => ['@other']],
    ]]);

    $instructions = (string) (new YaarpoolAgent)->instructions();

    expect($instructions)->not->toContain('You as agent are mentioned/triggered by these triggers')
        ->and($instructions)->not->toContain('@other');
});

it('instructs the agent to respond only to people who triggered it', function () {
    setAgentConfig();

    $instructions = (string) (new YaarpoolAgent)->instructions();

    expect($instructions)->toContain('Respond and create rides for ONLY people who have mentioned/triggered you.');
});

it('includes the current date and timezone for resolving relative phrases', function () {
    Carbon\Carbon::setTestNow('2026-06-02 09:30:00');
    setAgentConfig();

    $instructions = (string) (new YaarpoolAgent)->instructions();

    expect($instructions)->toContain(Carbon\Carbon::now()->format('l, j F Y H:i'))
        ->and($instructions)->toContain(Carbon\Carbon::now()->timezoneName);

    Carbon\Carbon::setTestNow();
});
