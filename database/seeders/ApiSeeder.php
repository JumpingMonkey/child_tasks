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
use App\Models\User;
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
        User::factory()->me()->create();
        User::factory()->admin()->create();
        $proof = ProofType::factory()->state(new Sequence(
            ['title' => ProofType::PROOF_TYPES[0]],
            ['title' => ProofType::PROOF_TYPES[1]],
            ['title' => ProofType::PROOF_TYPES[2]],
            ['title' => ProofType::PROOF_TYPES[3]],
            ['title' => ProofType::PROOF_TYPES[4]],
            ['title' => ProofType::PROOF_TYPES[5]],
            ['title' => ProofType::PROOF_TYPES[6]],
        ))
        ->count(7)
        ->create();

        $generalAvailableRegularTaskTemplate = [
            ['title' => 'Arrange the toys',
            'description' => 'Put all the toys in their places and take a photo of this beauty.',
            'proof_type_id' => 1,
            'is_active' => true],
            ['title' => 'Make the bed',
            'description' => 'Take two photos: First photo - the bed is spread out. Second photo - the bed is made.',
            'proof_type_id' => 3,
            'is_active' => true],
            ['title' => 'Brush your teeth',
            'description' => 'Brush your teeth for 2 minutes and then take a photo with a smile.',
            'proof_type_id' => 4,
            'is_active' => true],
            ['title' => 'Clean the room',
            'description' => 'Wipe the dust, sweep the floor. Take a picture of this purity.',
            'proof_type_id' => 5,
            'is_active' => true],
            ['title' => 'Wash the dishes',
            'description' => 'Take the photo of dirty and clean dishes.',
            'proof_type_id' => 3,
            'is_active' => true],
            ['title' => 'Feed your pet',
            'description' => 'Put food in a bowl of your pet and make photo of it.',
            'proof_type_id' => 1,
            'is_active' => true],
            ['title' => 'Do exercises',
            'description' => 'Do warm-up exercises for 5 minutes, and at the end take a photo of yourself.',
            'proof_type_id' => 4,
            'is_active' => true],
            ['title' => 'Read a book',
            'description' => 'Read a book and record a voice note about what you read.',
            'proof_type_id' => 5,
            'is_active' => true],
            ['title' => 'Clean up after pet',
            'description' => 'Cleen up after your pet and send a photo of his clean tray.',
            'proof_type_id' => 1,
            'is_active' => true],
            ['title' => 'Wipe off the dust',
            'description' => 'Wipe off the dust and take pictures of the cleanliness of the shelves.',
            'proof_type_id' => 1,
            'is_active' => true],
            ['title' => 'Do the homework',
            'description' => 'Take a photo of notebooks with completed homework.',
            'proof_type_id' => 1,
            'is_active' => true],
            ['title' => 'Water flowers',
            'description' => 'To water are all the flowers in the house. But a lot of water is also bad. Take a photo of flower pots.',
            'proof_type_id' => 1,
            'is_active' => true],
            ['title' => 'Take out the trash',
            'description' => 'Take a photo of a pet on the street, but let him have time to finish his affairs.',
            'proof_type_id' => 1,
            'is_active' => true],
            ['title' => 'Draw a picture',
            'description' => 'Draw a picture that we made a guess and take photo of it.',
            'proof_type_id' => 7,
            'is_active' => true],
            ['title' => 'Dress yourself',
            'description' => 'Dress yourself and take a photo in the mirror.',
            'proof_type_id' => 1,
            'is_active' => true],
            ['title' => 'Vacuum',
            'description' => 'Take a photo of the vacuum cleaner and the place where you vacuumed.',
            'proof_type_id' => 1,
            'is_active' => true],
            ['title' => 'Sweep',
            'description' => 'Sweep up and take photos of as much trash as you can collect.',
            'proof_type_id' => 1,
            'is_active' => true],
            ['title' => 'Get a good grade in school',
            'description' => 'Take a photo of your grades in your diary.',
            'proof_type_id' => 1,
            'is_active' => true],
            ['title' => 'Walk the dog',
            'description' => 'Take a photo of a pet on the street, but let him have time to finish his affairs.',
            'proof_type_id' => 1,
            'is_active' => true],
            ['title' => 'Call grandparents',
            'description' => 'Call your grandmother and find out how she is doing.',
            'proof_type_id' => 6,
            'is_active' => true],
            ['title' => 'Visit sections',
            'description' => 'Visit the section and take a photo of yourself there.',
            'proof_type_id' => 1,
            'is_active' => true],
            ['title' => 'Make a craft',
            'description' => 'Make a craft to your taste and take a photo of it.',
            'proof_type_id' => 1,
            'is_active' => true],
            ['title' => 'Serve a table',
            'description' => 'Set the table, place plates, forks, spoons, glasses, napkins and take a photo.',
            'proof_type_id' => 1,
            'is_active' => true],
            ['title' => 'Play with younger',
            'description' => 'Ask your brother what game he would like to play with you today. Take a photo while playing.',
            'proof_type_id' => 1,
            'is_active' => true],
            ['title' => 'Find an item',
            'description' => 'Find this object at house and make photo of it.',
            'proof_type_id' => 7,
            'is_active' => true],
        ];

        foreach($generalAvailableRegularTaskTemplate as $task){
            GeneralAvailableRegularTaskTemplate::factory()
            ->for(Schedule::factory())
            ->has(TaskImage::factory(), 'image')
            ->create($task);
        }
        

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
