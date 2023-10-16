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
use App\Models\TaskProofImage;
use App\Models\Timer;
use Carbon\Carbon;
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
            ['title' => ProofType::PROOF_TYPES[0]],
            ['title' => ProofType::PROOF_TYPES[1]],
        ))
        ->count(2)
        ->create();

        GeneralAvailableRegularTaskTemplate::factory()
            ->for($proof->random())
            ->for(Schedule::factory())
            ->has(TaskImage::factory(), 'image')
            ->count(10)
            ->create(['is_active' => true]);

        $j = 0;
        while($j < 10){
            $child = Child::factory()->create();
            $prem = fake()->randomElement([true, false]);
            $premUntil = $prem ? Carbon::now()->addDays(30)->toDateTimeString() : null;
            
            $adult = Adult::factory()
                ->hasAttached($child, [
                    'adult_type' => fake()->randomElement([
                        'Father',
                        'Mother',
                        'Grandma',
                        'Grandpa',
                    ]),
                ])
                ->create(['is_premium' => $prem, 'until' => $premUntil]);
                
                $proofTypeForOneDayTask = $proof->random();
                
                if ($proofTypeForOneDayTask->title == 'image'){
                    OneDayTask::factory()
                        ->for($proofTypeForOneDayTask)
                        ->for($child)
                        ->for($adult)
                        ->has(TaskProofImage::factory(), 'imageProof')
                        ->has(TaskImage::factory(), 'image')
                        ->create();
                } elseif ($proofTypeForOneDayTask->title == 'timer') {
                    OneDayTask::factory()
                        ->for($proofTypeForOneDayTask)
                        ->for($child)
                        ->for($adult)
                        ->has(TaskImage::factory(), 'image')
                        ->create();
                }
            

            
            $k = 0;
            while($k < 10){
                $taskTemplate = RegularTaskTemplate::factory()
                    ->for($proof->random())
                    ->for(Schedule::factory())
                    ->has(TaskImage::factory(), 'image')
                    ->for($child)
                    ->for($adult)
                    ->create([
                        'is_general_available' => true, 
                        'is_active' => fake()->randomElement([true, false])
                ]);

                if($taskTemplate->is_active){
                    if($taskTemplate->proofType->title == 'timer'){
                        RegularTask::factory()
                        ->for($taskTemplate)
                        ->create();
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
