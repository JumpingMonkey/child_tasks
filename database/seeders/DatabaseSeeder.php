<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Reward;
use App\Models\Task;
use App\Models\TaskStatus;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $users = User::factory()->count(40)->state(new Sequence(
            ['is_parent' => 1],
            ['is_parent' => 0],
        ))->create();

        $users->each(function($user) use($users){
            if($user->is_parent == 0){
                $user->user_id = $users->filter(function($value, $key){
                    return $value->is_parent == 1;
                })->random()->id;
            }
            $user->save();
            
        });

        TaskStatus::factory()->count(4)->state(new Sequence(
            ['name' => 'new'],
            ['name' => 'in progress'],
            ['name' => 'review'],
            ['name' => 'overdue'],
        ))->create();

        $tasks = Task::factory()->count(100)->make()
            ->each(function($task) use ($users){
                $task->user_id = $users->filter(function($value, $key){
                    return $value->is_parent == 1;
                })->random()->id;
                
                $task->executor_id = $users->filter(function($value, $key){
                    return $value->is_parent == 0;
                })->random()->id;

                $task->save();
        });

        $rewards = Reward::factory()->count(100)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
