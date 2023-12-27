<?php

namespace App\Console\Commands;

use App\Jobs\CreateTasksBySchedule;
use Illuminate\Console\Command;

class CreateRegularTasksBySchedule extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:create-regular-tasks-by-schedule';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        CreateTasksBySchedule::dispatch();
    }
}
