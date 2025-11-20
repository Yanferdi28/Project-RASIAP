<?php

namespace App\Observers;

use App\Models\ArsipUnit;
use App\Models\User;
use App\Notifications\ArsipUnitCreatedNotification;
use App\Notifications\ArsipUnitUpdatedNotification;
use App\Notifications\ArsipUnitDeletedNotification;

class ArsipUnitObserver
{
    /**
     * Handle the ArsipUnit "created" event.
     */
    public function created(ArsipUnit $arsipUnit): void
    {
        $admins = User::role(['admin', 'superadmin'])->get();
        foreach ($admins as $admin) {
            $admin->notify(new ArsipUnitCreatedNotification($arsipUnit));
        }
    }

    /**
     * Handle the ArsipUnit "updated" event.
     */
    public function updated(ArsipUnit $arsipUnit): void
    {
        $admins = User::role(['admin', 'superadmin'])->get();
        foreach ($admins as $admin) {
            $admin->notify(new ArsipUnitUpdatedNotification($arsipUnit));
        }
    }

    /**
     * Handle the ArsipUnit "deleted" event.
     */
    public function deleted(ArsipUnit $arsipUnit): void
    {
        $description = $arsipUnit->uraian_informasi ?? 'Arsip Unit ID: ' . $arsipUnit->id_berkas;
        $admins = User::role(['admin', 'superadmin'])->get();
        foreach ($admins as $admin) {
            $admin->notify(new ArsipUnitDeletedNotification($description));
        }
    }
}
