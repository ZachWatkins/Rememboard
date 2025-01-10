<?php

namespace App\Services\File;

use Sabre\VObject;
use App\Models\Event;
use App\Services\AddressParsingService;

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

        $addressParser = app(AddressParsingService::class);
        $vcalendar = VObject\Reader::read(fopen($realpath, 'r'), VObject\Reader::OPTION_FORGIVING);
        $names = [];

        foreach ($vcalendar->VEVENT as $vevent) {
            $name = $vevent->SUMMARY->getValue();
            if (!\in_array($name, $names, true)) {
                $names[] = $name;
                $address = $vevent->LOCATION?->getValue() ?? '';
                yield new Event([
                    'name' => $name,
                    'description' => $vevent->DESCRIPTION?->getValue() ?? '',
                    'start_date' => $vevent->DTSTART->getDateTime()->format('Y-m-d H:i:s'),
                    'end_date' => $vevent->DTEND?->getDateTime()->format('Y-m-d H:i:s') ?? null,
                    'address' => $address,
                    'is_trip' => $address ? true : false,
                    'city' => $addressParser->getCity($address),
                    'state' => $addressParser->getState($address),
                    'zip' => $addressParser->getZip($address),
                    'country' => $addressParser->getCountry($address),
                ]);
            }
        }
    }
}
