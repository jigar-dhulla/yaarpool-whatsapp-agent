<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\UserSetting;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<UserSetting>
 */
class UserSettingFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'sender_jid' => fake()->numerify('############').'@s.whatsapp.net',
            'default_from_location' => fake()->city(),
            'default_to_location' => null,
        ];
    }
}
