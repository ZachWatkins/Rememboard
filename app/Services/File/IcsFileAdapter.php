<?php

namespace App\Services\File;

use App\Models\Event;
use Illuminate\Support\Collection;
use Sabre\VObject;

class IcsFileAdapter
{
    /**
     * Get events from an .ics file.
     * @param string $path Path to a file in app/private.
     */
    public function getEvents(string $path): Collection
    {
        if ('ics' !== pathinfo($path, PATHINFO_EXTENSION)) {
            throw new \InvalidArgumentException('File must be an .ics file');
        }

        $path = storage_path('app/private/' . $path);
        if (!file_exists($path)) {
            throw new \InvalidArgumentException('File does not exist');
        }

        $vcalendar = VObject\Reader::read(fopen($path, 'r'), VObject\Reader::OPTION_FORGIVING);

        $events = collect($vcalendar->VEVENT)
            ->map(function (VObject\Component\VEvent $vevent) {
                $event = new Event();
                $event->name = $vevent->SUMMARY->getValue();
                $event->description = $vevent->DESCRIPTION?->getValue() ?? '';
                $event->start_date = $vevent->DTSTART->getDateTime()->format('Y-m-d H:i:s');
                $event->end_date = $vevent->DTEND->getDateTime()->format('Y-m-d H:i:s');
                $event->address = $vevent->LOCATION?->getValue() ?? '';
                return $event;
            });

        return $events;
    }
}
