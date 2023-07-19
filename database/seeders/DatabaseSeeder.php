<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Task;
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
        $users = User::factory()->count(4)->state(new Sequence(
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

        // $parents = $users->filter(function($value){
        //     return $value->is_parent == 1;
        // });

        // dd($users);

        $tasks = Task::factory()->count(10)->make()
            ->each(function($task) use ($users){
                $task->user_id = $users->filter(function($value, $key){
                    return $value->is_parent == 1;
                })->random()->id;
                
                $task->executor_id = $users->filter(function($value, $key){
                    return $value->is_parent == 0;
                })->random()->id;

                $task->save();
        });

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
