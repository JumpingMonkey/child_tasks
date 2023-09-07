<?php

namespace Database\Seeders;

use App\Models\Adult;
use App\Models\Child;
use App\Models\ProofType;
use App\Models\RegularTask;
use App\Models\RegularTaskTemplate;
use App\Models\Schedule;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class ApiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $proof = ProofType::factory()->state(new Sequence(
            ['title' => 'image'],
            ['title' => 'timer'],
        ))
        ->count(2)
        ->create();

        $taskTemplate = RegularTaskTemplate::factory()
            ->for($proof->random())
            ->for(Schedule::factory())
            ->count(10)
            ->create();

        $j = 0;
        while($j < 10){
            $child = Child::factory()->create();
            $adult = Adult::factory()
                ->hasAttached($child, [
                    'adult_type' => fake()->randomElement([
                        'Father',
                        'Mother',
                        'Grandma',
                        'Grandpa',
                    ]),
                ])
                ->create();

                
            RegularTask::factory()
            ->for($taskTemplate->random())
            ->for($adult)
            ->for($child)
            ->count(1)
            ->create();
            $j++;
        }

        
    }
}
