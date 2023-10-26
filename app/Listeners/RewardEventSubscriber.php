<?php

namespace App\Listeners;

use App\Events\ChildRewardWasClaimed;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Events\Dispatcher;

class RewardEventSubscriber
{
    /**
     * Handle reward update events.
     */
    public function handleUpdateReward(ChildRewardWasClaimed $event) {
       
        $child = $event->childReward->child;

        $child->update([
            'coins' => $child->coins - $event->childReward->price,
        ]);
    }
 
    /**
     * Register the listeners for the subscriber.
     */
    public function subscribe(Dispatcher $events): void
    {
        $events->listen(
            ChildRewardWasClaimed::class,
            [RewardEventSubscriber::class, 'handleUpdateReward']
        );
    }
}
