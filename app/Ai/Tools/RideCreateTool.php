<?php

declare(strict_types=1);

namespace App\Ai\Tools;

use App\Enums\RideType;
use App\Models\Ride;
use Carbon\Exceptions\InvalidFormatException;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use Stringable;

class RideCreateTool implements Tool
{
    public function __construct(
        protected ?string $chatJid = null,
        protected ?string $senderJid = null,
        protected ?string $senderName = null,
    ) {}

    public function name(): string
    {
        return 'ride_create';
    }

    public function description(): Stringable|string
    {
        return 'Publish a ride that a driver is offering. Use this when the user is offering or announcing a trip with available seats, not when they are looking for one.';
    }

    public function handle(Request $request): Stringable|string
    {
        try {
            $departsAt = $request->date('departs_at');
        } catch (InvalidFormatException) {
            return 'I could not work out the departure date and time from that. Could you be more specific (e.g. "today 6pm", "Sat 9:30am")?';
        }

        $ride = Ride::create([
            'type' => RideType::Offer,
            'chat_jid' => $this->chatJid,
            'sender_jid' => $this->senderJid,
            'sender_name' => $this->senderName,
            'from_location' => (string) $request['from'],
            'to_location' => (string) $request['to'],
            'when_text' => (string) $request['when_text'],
            'departs_at' => $departsAt,
            'seats' => max(1, (int) $request['seats']),
            'price_per_seat' => $request['price_per_seat'] ?? null,
            'vehicle' => $request['vehicle'] ?? null,
            'notes' => $request['notes'] ?? null,
        ]);

        $price = $ride->price_per_seat ? ' at '.$ride->price_per_seat.'/seat' : '';

        return sprintf(
            'Ride #%d published: %s → %s, departing %s, %d seat%s available%s. Passengers in the group will see it.',
            $ride->id,
            $ride->from_location,
            $ride->to_location,
            $ride->when_text,
            $ride->seats,
            $ride->seats === 1 ? '' : 's',
            $price,
        );
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'from' => $schema->string()
                ->description('Origin of the trip as stated by the driver.')
                ->required(),
            'to' => $schema->string()
                ->description('Destination of the trip.')
                ->required(),
            'when_text' => $schema->string()
                ->description('The driver\'s exact phrasing of when they depart, copied verbatim (e.g. "today 6pm", "Sat 9:30am"). Used for verification.')
                ->required(),
            'departs_at' => $schema->string()
                ->format('date-time')
                ->description('The same date/time normalized to ISO-8601 (e.g. "2026-05-19T18:00:00"). Resolve relative phrases ("today", "Saturday") using the current date provided in your instructions. If the driver only gave a vague time of day, pick a reasonable hour (morning = 09:00, evening = 18:00, night = 21:00).')
                ->required(),
            'seats' => $schema->integer()
                ->description('Number of empty seats the driver has to share.')
                ->min(1)
                ->max(8)
                ->required(),
            'price_per_seat' => $schema->string()
                ->description('Cost per seat, including currency if mentioned (e.g. "₹150"). Omit if the driver did not state a price.'),
            'vehicle' => $schema->string()
                ->description('Vehicle make/model or other identifying detail the driver shared.'),
            'notes' => $schema->string()
                ->description('Any extra context — luggage capacity, pickup flexibility, contact preferences.'),
        ];
    }
}
