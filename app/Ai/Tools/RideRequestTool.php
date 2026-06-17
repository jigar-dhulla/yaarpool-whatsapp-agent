<?php

declare(strict_types=1);

namespace App\Ai\Tools;

use App\Enums\RideType;
use App\Models\GroupSetting;
use App\Models\Ride;
use Carbon\Exceptions\InvalidFormatException;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use Stringable;

class RideRequestTool implements Tool
{
    public function __construct(
        protected ?string $chatJid = null,
        protected ?string $senderJid = null,
        protected ?string $senderName = null,
    ) {}

    public function name(): string
    {
        return 'ride_request';
    }

    public function description(): Stringable|string
    {
        return 'Publish a ride request from a passenger who is looking for a lift. Use this when the user wants to find a ride, not when they are offering one.';
    }

    public function handle(Request $request): Stringable|string
    {
        try {
            $departsAt = $request->date('departs_at');
        } catch (InvalidFormatException) {
            return 'I could not work out the date and time from that. Could you be more specific (e.g. "tomorrow 8am", "Fri 6pm")?';
        }

        $seats = max(1, (int) ($request['seats'] ?? 1));

        $settings = GroupSetting::forChat($this->chatJid);

        $from = $request['from'] ?? $settings?->default_from_location;
        $to = $request['to'] ?? $settings?->default_to_location;

        if (blank($from)) {
            return 'Where do you need to be picked up? This group has no default pickup location set.';
        }

        if (blank($to)) {
            return 'Where are you heading to?';
        }

        $ride = Ride::create([
            'type' => RideType::Request,
            'chat_jid' => $this->chatJid,
            'sender_jid' => $this->senderJid,
            'sender_name' => $this->senderName,
            'from_location' => (string) $from,
            'to_location' => (string) $to,
            'when_text' => (string) $request['when_text'],
            'departs_at' => $departsAt,
            'recurrence_days' => Ride::normalizeRecurrenceDays($request['repeat_days'] ?? null),
            'seats' => $seats,
            'notes' => $request['notes'] ?? null,
        ]);

        $repeats = $ride->isRecurring() ? ', repeats '.$ride->recurrenceLabel() : '';

        return sprintf(
            'Ride request #%d noted: %s → %s, %s%s, %d seat%s. Drivers in the group will see it.',
            $ride->id,
            $ride->from_location,
            $ride->to_location,
            $ride->when_text,
            $repeats,
            $ride->seats,
            $ride->seats === 1 ? '' : 's',
        );
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'from' => $schema->string()
                ->description('Pickup location as stated by the passenger (e.g. "Andheri East", "Sector 21 gate"). Omit when the passenger does not name a pickup point — the group\'s default origin will be assumed.'),
            'to' => $schema->string()
                ->description('Drop-off location as stated by the passenger. Omit when the passenger does not name a destination — the group\'s default destination, if configured, will be assumed.'),
            'when_text' => $schema->string()
                ->description('The passenger\'s exact phrasing of when they need the ride, copied verbatim (e.g. "tomorrow 8am", "Fri evening"). Used for verification.')
                ->required(),
            'departs_at' => $schema->string()
                ->format('date-time')
                ->description('The same date/time normalized to ISO-8601 (e.g. "2026-05-19T08:00:00"). Resolve relative phrases ("tomorrow", "Friday") using the current date provided in your instructions. If the passenger only gave a vague time of day, pick a reasonable hour (morning = 09:00, evening = 18:00, night = 21:00).')
                ->required(),
            'seats' => $schema->integer()
                ->description('Number of seats the passenger needs. Defaults to 1.')
                ->min(1)
                ->max(8),
            'repeat_days' => $schema->array()
                ->items($schema->string()->enum([
                    'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday', 'daily',
                ]))
                ->description('Set this only when the passenger wants the ride to repeat. Use ["daily"] for every day, or list the specific weekdays, e.g. ["monday","wednesday","friday"]. Omit entirely for a one-off ride. Still set `departs_at` to the next matching occurrence.'),
            'notes' => $schema->string()
                ->description('Any extra context the passenger shared — luggage, flexibility, contact preferences.'),
        ];
    }
}
