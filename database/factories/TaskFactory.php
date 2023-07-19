<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(),
            'description' => fake()->paragraph(),
            'status' => fake()->randomElement(['new', 'in progress', 'review', 'done',]),
            'planned_and_date' => fake()->dateTimeBetween('-1 week', '+1 week'),
            'is_image_required' => fake()->boolean(),
            'coins' => fake()->numberBetween(5,30),
        ];
    }
}
