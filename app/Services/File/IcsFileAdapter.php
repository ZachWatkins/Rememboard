<?php

namespace App\Services\File;

use App\Models\Event;
use Sabre\VObject;

class IcsFileAdapter
{
    /**
     * Get events from a .ics file.
     * @param string $path Path to a file in app/private.
     */
    public function getEvents(string $path): \Generator
    {
        if ('ics' !== pathinfo($path, PATHINFO_EXTENSION)) {
            throw new \InvalidArgumentException('File must be a .ics file: ' . $path);
        }

        $realpath = storage_path('app/private/' . $path);
        if (!file_exists($realpath)) {
            $paths = glob($realpath);
            if (!$paths) {
                throw new \InvalidArgumentException('File does not exist: ' . $path);
            }
            $realpath = $paths[0];
        }

        $vcalendar = VObject\Reader::read(fopen($realpath, 'r'), VObject\Reader::OPTION_FORGIVING);
        $names = [];

        foreach ($vcalendar->VEVENT as $vevent) {
            $name = $vevent->SUMMARY->getValue();
            if (!\in_array($name, $names, true)) {
                $names[] = $name;
                $event = new Event();
                $event->name = $name;
                $event->description = $vevent->DESCRIPTION?->getValue() ?? '';
                $event->start_date = $vevent->DTSTART->getDateTime()->format('Y-m-d H:i:s');
                $event->end_date = $vevent->DTEND?->getDateTime()->format('Y-m-d H:i:s') ?? null;
                $event->address = $vevent->LOCATION?->getValue() ?? '';
                if ($event->address) {
                    $event->is_trip = true;
                }
                yield $event;
            }
        }
    }
}
