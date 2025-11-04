<?php

namespace App\Listeners;

use App\Events\ArsipUnitCreated;
use App\Models\User;
use App\Notifications\ArsipUnitCreatedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class NotifyOperatorsAboutNewArsipUnit
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(ArsipUnitCreated $event): void
    {
        // Send notification to all users with 'operator' role
        $operators = User::role('operator')->get();
        
        foreach ($operators as $operator) {
            $operator->notify(new ArsipUnitCreatedNotification($event->arsipUnit));
        }
    }
}
