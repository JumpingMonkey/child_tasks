<?php

namespace App\Jobs;

use App\Models\Adult;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class SwithPremium implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $adults = Adult::where('is_premium', 1)->get();
            $adults->each(function($adult){
                if($adult->until < now() AND !empty($adult->until)){
                    $adult->is_premium = 0;
                    $adult->save();
                }
            });
    }
}
