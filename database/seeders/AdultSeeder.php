<?php

namespace Database\Seeders;

use App\Models\Adult;
use App\Models\Child;
use Database\Factories\ChildFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdultSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $adults  = Adult::factory()
        //     ->has(Child::factory()->count(1))
        //     ->count(10)
        //     ->create();
        
        // Child::factory()
        //     ->count(10)
        //     ->for(Adult::factory())
        //     ->create();

        $i = 0;
        while($i < 10)
            {
                Adult::factory()
                ->count(1)
                ->hasAttached(Child::factory(), [
                    'adult_type' => fake()->randomElement([
                        'Father',
                        'Mother',
                        'Grandma',
                        'Grandpa',
                    ]),
                ])
                ->create();
                $i++;
            }
        // $adults->each(function($adult){
        //         $adult->children()->attach(Child::factory()->make());
        //     });
        

    }
}
