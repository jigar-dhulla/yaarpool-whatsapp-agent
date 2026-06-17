<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\RideType;
use App\Enums\Weekday;
use Database\Factories\RideFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

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
        'recurrence_days',
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
            'recurrence_days' => 'array',
            'seats' => 'integer',
        ];
    }

    /**
     * Normalize the agent's `repeat_days` selection (weekday names or "daily")
     * into weekday values ordered Monday → Sunday. Invalid tokens are dropped
     * and duplicates collapsed. Returns null for a one-off ride.
     *
     * @param  array<int, string>|null  $input
     * @return array<int, string>|null
     */
    public static function normalizeRecurrenceDays(?array $input): ?array
    {
        if (empty($input)) {
            return null;
        }

        $weekdays = array_column(Weekday::cases(), 'value');

        if (in_array('daily', $input, true)) {
            return $weekdays;
        }

        $days = array_values(array_intersect($weekdays, $input));

        return $days === [] ? null : $days;
    }

    public function isRecurring(): bool
    {
        return ! empty($this->recurrence_days);
    }

    /**
     * The next time this ride departs. A one-off ride departs once at
     * `departs_at`; a recurring ride departs on its next matching weekday at the
     * same time of day, so it never lingers in the past showing a stale date.
     */
    public function nextOccurrence(?Carbon $from = null): Carbon
    {
        if (! $this->isRecurring()) {
            return $this->departs_at;
        }

        $from ??= Carbon::now();

        $candidate = $from->copy()->setTimeFrom($this->departs_at);

        for ($day = 0; $day < 7; $day++) {
            if ($candidate->greaterThanOrEqualTo($from)
                && in_array(strtolower($candidate->format('l')), $this->recurrence_days, true)) {
                return $candidate;
            }

            $candidate = $candidate->addDay();
        }

        return $this->departs_at;
    }

    /**
     * A WhatsApp-friendly label for the recurrence (e.g. "daily", "Mon/Wed/Fri").
     * Empty string when the ride is a one-off.
     */
    public function recurrenceLabel(): string
    {
        $days = $this->recurrence_days;

        if (empty($days)) {
            return '';
        }

        if (count($days) === 7) {
            return 'daily';
        }

        return (new Collection($days))
            ->map(fn (string $day): string => Weekday::from($day)->short())
            ->implode('/');
    }
}
