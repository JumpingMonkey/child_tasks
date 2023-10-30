<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AdultAccountSettings>
 */
class AdultAccountSettingsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'is_child_notification_enabled' => false,
            'is_adult_notification_enabled' => false,
            'language' => 'en',
        ];
    }
}
