<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\RideType;
use Database\Factories\RideFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
