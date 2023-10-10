<?php

namespace App\Domains\Event\Observers;

use App\Models\Event;
use Illuminate\Support\Carbon;

class RecurrenceOserver
{
    /**
     * Handle the Event "created" event.
     */
    public function created(Event $event): void
    {
        if ($event->frequency && $event->repeat_until) {
            switch ($event->frequency) {
                case 'daily':
                    $func = 'addDay';
                    break;
                case 'weekly':
                    $func = 'addWeek';
                    break;
                case 'monthly':
                    $func = 'addMonth';
                    break;
                case 'yearly':
                    $func = 'addYear';
                    break;
            }

            $start = Carbon::parse($event->start);
            $end = Carbon::parse($event->end);

            while ($end < Carbon::parse($event->repeat_until)) {
                $end = $end->$func();
                Event::create([
                    'title' => $event->title,
                    'description' => $event->description,
                    'start' => $start->$func(),
                    'end' => $end,
                    'event_id' => $event->id,
                ]);
            }
        }

    }

    /**
     * Handle the Event "updated" event.
     */
    public function updated(Event $event): void
    {
        //
    }

    /**
     * Handle the Event "deleted" event.
     */
    public function deleted(Event $event): void
    {
        //
    }

    /**
     * Handle the Event "restored" event.
     */
    public function restored(Event $event): void
    {
        //
    }

    /**
     * Handle the Event "force deleted" event.
     */
    public function forceDeleted(Event $event): void
    {
        //
    }
}
