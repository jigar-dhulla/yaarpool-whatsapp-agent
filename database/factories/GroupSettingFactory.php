<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\GroupSetting;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<GroupSetting>
 */
class GroupSettingFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'chat_jid' => fake()->numerify('############').'@g.us',
            'default_from_location' => fake()->city(),
            'default_to_location' => null,
        ];
    }
}
