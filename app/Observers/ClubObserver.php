<?php

namespace App\Observers;

use App\Models\tmp\Club;

class ClubObserver
{
    public function deleting(Club $club)
    {
        // Delete related events
        $club->events()->each(function ($event) {
            // Delete related reservations
            $event->reservations()->delete();
            $event->delete();
        });
    }
}
