<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Schedule>
 */
class ScheduleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'monday' => fake()->boolean(50),
            'tuesday' => fake()->boolean(50),
            'wednesday' => fake()->boolean(50),
            'thursday' => fake()->boolean(50),
            'friday' => fake()->boolean(50),
            'saturday' => fake()->boolean(50),
            'sunday' => fake()->boolean(50),
        ];
    }
}
