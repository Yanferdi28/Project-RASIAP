<?php

namespace App\Listeners;

use App\Models\User;
use App\Notifications\UserRegisteredNotification;
use Illuminate\Auth\Events\Registered;

class NotifyAdminsAboutNewUser
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
    public function handle(Registered $event): void
    {
        // Get all admin and superadmin users
        $admins = User::role(['admin', 'superadmin'])->get();

        // Send notification to each admin if there are any
        if ($admins->count() > 0) {
            foreach ($admins as $admin) {
                $admin->notify(new UserRegisteredNotification($event->user));
            }
        }
    }
}
