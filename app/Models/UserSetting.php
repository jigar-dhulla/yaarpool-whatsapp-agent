<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\UserSettingFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSetting extends Model
{
    /** @use HasFactory<UserSettingFactory> */
    use HasFactory;

    protected $fillable = [
        'sender_jid',
        'default_from_location',
        'default_to_location',
    ];

    /**
     * The personal defaults saved by a sender, or null when none are set.
     */
    public static function forSender(?string $senderJid): ?self
    {
        if ($senderJid === null) {
            return null;
        }

        return static::query()->where('sender_jid', $senderJid)->first();
    }
}
