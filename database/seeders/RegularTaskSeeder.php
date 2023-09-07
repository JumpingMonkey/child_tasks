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

class RegularTaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $regTask = RegularTaskTemplate::factory()
        //     ->for(ProofType::factory()->state(new Sequence(
        //         ['title' => 'image'],
        //         ['title' => 'timer'],
        //     )))
        //     ->for(Schedule::factory())
        //     ->for(Adult::factory())
        //     ->for(Child::factory())
            
        //     ->create();
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

            $i = 0;
            while($i < 10){
                RegularTask::factory()
                ->for($taskTemplate->random())
                ->for(Adult::factory())
                ->for(Child::factory())
                ->count(1)
                ->create();
                $i++;
            }
    }
}
