<?php

declare(strict_types=1);

namespace App\Ai\Tools;

use App\Models\UserSetting;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use Stringable;

class UserSettingsTool implements Tool
{
    public function __construct(
        protected ?string $senderJid = null,
    ) {}

    public function name(): string
    {
        return 'user_settings';
    }

    public function description(): Stringable|string
    {
        return 'Save or show the user\'s personal ride defaults — their usual starting and ending location. Use this when the user asks to remember their usual route or asks what their saved defaults are. Pass `from` and/or `to` to save; pass neither to show the current defaults.';
    }

    public function handle(Request $request): Stringable|string
    {
        if ($this->senderJid === null) {
            return 'I cannot verify who you are right now, so I can\'t manage your personal defaults.';
        }

        $from = $request['from'] ?? null;
        $to = $request['to'] ?? null;

        $settings = UserSetting::forSender($this->senderJid);

        if (blank($from) && blank($to)) {
            if ($settings === null) {
                return 'You have no personal defaults saved yet. Tell me your usual route (e.g. "I usually travel from Wakad to Hinjewadi") and I\'ll remember it.';
            }

            return $this->summarise($settings, 'Your saved defaults');
        }

        if ($settings === null && blank($from)) {
            return 'What is your usual starting location? I need that before I can save a default destination.';
        }

        $settings ??= new UserSetting(['sender_jid' => $this->senderJid]);

        if (filled($from)) {
            $settings->default_from_location = (string) $from;
        }

        if (filled($to)) {
            $settings->default_to_location = (string) $to;
        }

        $settings->save();

        return $this->summarise($settings, 'Saved').' I\'ll use these when you don\'t mention a location.';
    }

    protected function summarise(UserSetting $settings, string $prefix): string
    {
        return $settings->default_to_location === null
            ? sprintf('%s: usually starting from %s.', $prefix, $settings->default_from_location)
            : sprintf('%s: usually %s → %s.', $prefix, $settings->default_from_location, $settings->default_to_location);
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'from' => $schema->string()
                ->description('The user\'s usual starting location (e.g. "Wakad", "Sector 21 gate"). Omit when the user is not setting it.'),
            'to' => $schema->string()
                ->description('The user\'s usual destination (e.g. "Hinjewadi", "the airport"). Omit when the user is not setting it.'),
        ];
    }
}
