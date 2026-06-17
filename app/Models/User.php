<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

#[Fillable(['name', 'phone', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Create or update the user behind a WhatsApp sender JID, keyed by their
     * phone number. The name is refreshed whenever a non-empty one is known,
     * but an existing name is never clobbered by a blank update. Email stays
     * optional — these users authenticate through WhatsApp, not the web.
     */
    public static function registerFromWhatsApp(string $senderJid, ?string $name = null): self
    {
        $phone = static::phoneFromJid($senderJid);

        $user = static::firstOrNew(['phone' => $phone]);

        $name = is_string($name) ? trim($name) : '';

        if ($name !== '') {
            $user->name = $name;
        } elseif (! $user->exists) {
            $user->name = $phone;
        }

        $user->save();

        return $user;
    }

    /**
     * Reduce a WhatsApp JID such as "919999999999:5@s.whatsapp.net" to the
     * bare number ("919999999999") used as the user's natural key.
     */
    protected static function phoneFromJid(string $senderJid): string
    {
        return (string) Str::of($senderJid)->before('@')->before(':');
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
