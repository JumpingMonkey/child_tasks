<?php

namespace Database\Seeders;

use App\Models\Adult;
use App\Models\Child;
use App\Models\ChildReward;
use App\Models\ChildRewardImage;
use App\Models\GeneralAvailableRegularTask;
use App\Models\GeneralAvailableRegularTaskTemplate;
use App\Models\Image;
use App\Models\OneDayTask;
use App\Models\ProofType;
use App\Models\RegularTask;
use App\Models\RegularTaskTemplate;
use App\Models\Schedule;
use App\Models\TaskImage;
use App\Models\Timer;
use Database\Factories\ImageFactory;
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

        GeneralAvailableRegularTaskTemplate::factory()
            ->for($proof->random())
            ->for(Schedule::factory())
            ->count(10)
            ->create(['status' => true]);

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
                
                $proofTypeForOneDayTask = $proof->random();
                
                if ($proofTypeForOneDayTask->title == 'image'){
                    OneDayTask::factory()
                        ->for($proofTypeForOneDayTask)
                        ->for($child)
                        ->for($adult)
                        // ->has(TaskImage::factory(), 'image')
                        ->create([
                            'picture_proof' => null,
                        ]);
                } elseif ($proofTypeForOneDayTask->title == 'timer') {
                    OneDayTask::factory()
                        ->for($proofTypeForOneDayTask)
                        ->for($child)
                        ->for($adult)
                        // ->has(TaskImage::factory(), 'image')
                        ->has(Timer::factory())
                        ->create();
                }
            

            
            $k = 0;
            while($k < 10){
                $taskTemplate = RegularTaskTemplate::factory()
                    ->for($proof->random())
                    ->for(Schedule::factory())
                    ->for($child)
                    ->for($adult)
                    ->create([
                        'is_general_available' => true, 
                        'status' => fake()->randomElement([true, false])
                ]);

                if($taskTemplate->status){
                    if($taskTemplate->proofType->title == 'timer'){
                        RegularTask::factory()
                        ->for($taskTemplate)
                        ->has(Timer::factory())
                        ->create(['picture_proof' => null]);
                    } else {
                        RegularTask::factory()
                        ->for($taskTemplate)
                        ->create();
                    }
                }
                $k++;
            }

            ChildReward::factory()
                ->for($adult)
                ->for($child)
                ->hasImage()
                ->create();
                
            $j++;
        }
    }
}
