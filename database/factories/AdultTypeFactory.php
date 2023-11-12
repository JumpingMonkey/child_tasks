<?php

namespace Database\Factories;

use App\Models\AdultType;
use Filament\SpatieLaravelTranslatablePlugin;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\App;
use Filament\Resources\Concerns\Fillament;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AdultType>
 */
class AdultTypeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $locales = filament('spatie-laravel-translatable')->getDefaultLocales();
        $value = [];
        foreach($locales as $locale) {
            $value[$locale] = fake()->word();
        }
        return [
            'title' => $value,
        ];
    }
}
