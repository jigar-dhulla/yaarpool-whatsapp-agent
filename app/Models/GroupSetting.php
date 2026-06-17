<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\GroupSettingFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupSetting extends Model
{
    /** @use HasFactory<GroupSettingFactory> */
    use HasFactory;

    protected $fillable = [
        'chat_jid',
        'default_from_location',
        'default_to_location',
    ];

    /**
     * The admin-configured defaults for a chat, or null when none are set.
     */
    public static function forChat(?string $chatJid): ?self
    {
        if ($chatJid === null) {
            return null;
        }

        return static::query()->where('chat_jid', $chatJid)->first();
    }
}
