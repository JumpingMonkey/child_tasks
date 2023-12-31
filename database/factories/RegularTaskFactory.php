<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RegularTask>
 */
class RegularTaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'status' => 'should do',
            'start_date' => Carbon::now()->startOfDay()->toDateTimeString(),
            'end_date' => Carbon::now()->endOfDay()->toDateTimeString(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
