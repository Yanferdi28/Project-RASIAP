<?php

namespace App\Listeners;

use App\Events\ArsipUnitCreated;
use App\Models\User;
use App\Notifications\ArsipUnitCreatedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class NotifyOperatorsAboutNewArsipUnit implements ShouldQueue
{
    use InteractsWithQueue;

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
        try {
            // Send notification to all users with 'operator' role
            $operators = User::role('operator')->get();

            foreach ($operators as $operator) {
                $operator->notify(new ArsipUnitCreatedNotification($event->arsipUnit));
            }
        } catch (\Exception $e) {
            // Log the error for debugging
            Log::error('Failed to send notification to operators', [
                'error' => $e->getMessage(),
                'arsip_unit_id' => $event->arsipUnit->id_berkas ?? null,
                'trace' => $e->getTraceAsString()
            ]);

            // Throw exception to handle by queue system
            throw $e;
        }
    }
}
