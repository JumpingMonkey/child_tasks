<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RegularTaskTemplate>
 */
class RegularTaskTemplateFactory extends Factory
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
            'is_general_available' => 0,
            'coins' => 1,
            // 'adult_id' => 1,
            // 'child_id' => 1,
            'expected_duration' => 60,
            // 'proof_type_id' => ,
            // 'schedule_id' => 
        ];
    }
}
