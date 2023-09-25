<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OneDayTask>
 */
class OneDayTaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(3),
            'description' => fake()->paragraph(1),
            'icon' => fake()->image(),
            'image' => fake()->image(),
            'coins' => 1,
            'expected_duration' => 60,
            'picture_proof' => fake()->image(),
            'proof_type_id' => 1,
            'start_date' => Carbon::now()->startOfDay()->toDateTimeString(),
            'end_date' => Carbon::now()->endOfDay()->toDateTimeString(),
        ];
    }
}
