<?php

namespace App\Observers;

use App\Models\BerkasArsip;
use App\Models\User;
use App\Notifications\BerkasArsipCreatedNotification;
use App\Notifications\BerkasArsipUpdatedNotification;
use App\Notifications\BerkasArsipDeletedNotification;

class BerkasArsipObserver
{
    /**
     * Handle the BerkasArsip "created" event.
     */
    public function created(BerkasArsip $berkasArsip): void
    {
        $admins = User::role(['admin', 'superadmin'])->get();
        foreach ($admins as $admin) {
            $admin->notify(new BerkasArsipCreatedNotification($berkasArsip));
        }
    }

    /**
     * Handle the BerkasArsip "updated" event.
     */
    public function updated(BerkasArsip $berkasArsip): void
    {
        $admins = User::role(['admin', 'superadmin'])->get();
        foreach ($admins as $admin) {
            $admin->notify(new BerkasArsipUpdatedNotification($berkasArsip));
        }
    }

    /**
     * Handle the BerkasArsip "deleted" event.
     */
    public function deleted(BerkasArsip $berkasArsip): void
    {
        $admins = User::role(['admin', 'superadmin'])->get();
        foreach ($admins as $admin) {
            $admin->notify(new BerkasArsipDeletedNotification($berkasArsip->nama_berkas));
        }
    }
}
