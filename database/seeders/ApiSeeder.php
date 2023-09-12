<?php

namespace Database\Seeders;

use App\Models\Adult;
use App\Models\Child;
use App\Models\ProofType;
use App\Models\RegularTask;
use App\Models\RegularTaskTemplate;
use App\Models\Schedule;
use App\Models\Timer;
use Database\Factories\TimerFactory;
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
        
        $k = 0;
        while($k < 10){
        RegularTaskTemplate::factory()
            ->for($proof->random())
            ->for(Schedule::factory())
            ->create(['is_general_available' => true]);
            $k++;
        }
        $taskTemplate = RegularTaskTemplate::all();
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

            $task = $taskTemplate->random();
            
            if($task->proofType->title == 'timer'){
                RegularTask::factory()
                ->for($task)
                ->for($adult)
                ->for($child)
                ->has(Timer::factory())
                ->create(['picture_proof' => null]);
            } else {
                RegularTask::factory()
                ->for($task)
                ->for($adult)
                ->for($child)
                ->create();
            }
            
            $j++;
        }

        
    }
}
