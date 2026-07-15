<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\RideType;
use Database\Factories\RideFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class Ride extends Model
{
    /** @use HasFactory<RideFactory> */
    use HasFactory;

    protected $fillable = [
        'type',
        'chat_jid',
        'sender_jid',
        'sender_name',
        'from_location',
        'to_location',
        'when_text',
        'departs_at',
        'seats',
        'price_per_seat',
        'vehicle',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'type' => RideType::class,
            'departs_at' => 'datetime',
            'seats' => 'integer',
        ];
    }

    /**
     * @return HasMany<RidePassenger, $this>
     */
    public function passengers(): HasMany
    {
        return $this->hasMany(RidePassenger::class);
    }

    /**
     * Seats already reserved by passengers who joined this ride. The `seats`
     * column keeps the total the driver published; availability is derived so
     * a passenger leaving (or being removed) restores seats automatically.
     */
    public function seatsTaken(): int
    {
        return (int) $this->passengers()->sum('seats');
    }

    public function seatsAvailable(): int
    {
        return max(0, $this->seats - $this->seatsTaken());
    }

    /**
     * Chat members (other than this ride's poster) whose saved commute —
     * usual route plus office days — matches this ride's route and travel
     * day(s). Scoped to senders previously seen in this chat (as a poster or
     * a passenger) so we never surface someone's personal defaults to a
     * group they've never been part of.
     *
     * @return Collection<int, array{name: string, days: string}>
     */
    public function matchingDailyTravellers(): Collection
    {
        if ($this->chat_jid === null || $this->sender_jid === null) {
            return new Collection;
        }

        $from = Str::lower(trim($this->from_location));
        $to = Str::lower(trim($this->to_location));
        $travelDays = [Str::lower($this->departs_at->format('l'))];

        // Map to plain arrays: these queries only select sender_jid/sender_name
        // (no primary key), and Eloquent\Collection::merge()/unique() key by
        // getKey(), which would be null here and silently drop every row.
        $toSenderArray = fn ($model): array => ['sender_jid' => $model->sender_jid, 'sender_name' => $model->sender_name];

        $knownSenders = static::query()
            ->where('chat_jid', $this->chat_jid)
            ->whereNotNull('sender_jid')
            ->where('sender_jid', '!=', $this->sender_jid)
            ->get(['sender_jid', 'sender_name'])
            ->map($toSenderArray)
            ->merge(
                RidePassenger::query()
                    ->whereHas('ride', fn ($query) => $query->where('chat_jid', $this->chat_jid))
                    ->where('sender_jid', '!=', $this->sender_jid)
                    ->get(['sender_jid', 'sender_name'])
                    ->map($toSenderArray)
            )
            ->unique('sender_jid');

        if ($knownSenders->isEmpty()) {
            return new Collection;
        }

        $settingsBySender = UserSetting::query()
            ->whereIn('sender_jid', $knownSenders->pluck('sender_jid'))
            ->whereNotNull('office_days')
            ->get()
            ->keyBy('sender_jid');

        return $knownSenders
            ->map(function (array $sender) use ($settingsBySender, $from, $to, $travelDays) {
                $settings = $settingsBySender->get($sender['sender_jid']);

                if ($settings === null || empty($settings->office_days)) {
                    return null;
                }

                if (Str::lower(trim((string) $settings->default_from_location)) !== $from
                    || Str::lower(trim((string) $settings->default_to_location)) !== $to) {
                    return null;
                }

                if (array_intersect($travelDays, $settings->office_days) === []) {
                    return null;
                }

                return [
                    'name' => $sender['sender_name'] ?? 'Someone',
                    'days' => $settings->officeDaysLabel() ?? '',
                ];
            })
            ->filter()
            ->values();
    }

    /**
     * A friendly nudge naming chat members whose saved commute matches this
     * ride, or null when there is no one to suggest.
     */
    public function dailyTravellerSuggestion(): ?string
    {
        $matches = $this->matchingDailyTravellers();

        if ($matches->isEmpty()) {
            return null;
        }

        $names = $matches
            ->map(fn (array $match): string => sprintf('%s (%s)', $match['name'], $match['days']))
            ->implode(', ');

        return "👀 Matches on this route: {$names}. Might be worth reaching out to see if they're going.";
    }
}
