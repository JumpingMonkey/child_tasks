<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class RewardFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $users = User::pluck('id')->toArray();
        
        return [
            'title' => fake()->sentence(),
            'description' => fake()-> paragraph(),
            'price' => random_int(1, 30),
            'status' => fake()->boolean(),
            'user_id' => fake()->randomElement($users),
        ];
    }
}
