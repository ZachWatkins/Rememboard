<?php

use App\Models\Event;
use App\Models\Participant;
use App\Services\GeolocationService;
use Illuminate\Foundation\Inspiring;
use App\Services\File\IcsFileAdapter;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('import:events', function (IcsFileAdapter $adapter, GeolocationService $geolocationService) {
    $path = $this->ask('Enter the path or pattern for the ICS file in app/private');
    $tripsOnly = 'y' === strtolower($this->ask('Only import events with addresses and mark them as trips? Y/n'));
    $requestCoordinates = 'y' === strtolower($this->ask('Fetch coordinates from the geocoding service? Y/n'));
    $promptEachEvent = 'y' === strtolower($this->ask('Select which events to import? Y/n'));

    $skipped = 0;
    $duplicates = 0;
    $ids = [];
    foreach ($adapter->getEvents($path) as $event) {
        if (Event::where('name', $event->name)->exists()) {
            $duplicates++;
            continue;
        }
        if ($tripsOnly && !$event->address) {
            $skipped++;
            continue;
        }
        if ($promptEachEvent && 'y' !== strtolower($this->ask("Import \"{$event->name}\"? Y/n"))) {
            $skipped++;
            continue;
        }
        if ($tripsOnly || 'y' === strtolower($this->ask("Is the event {$event->name} a trip? Y/n"))) {
            $event->is_trip = true;
        }
        if ($requestCoordinates && $event->address) {
            $coords = $geolocationService->getCoordinates($event->address);
            $event->latitude = $coords['latitude'];
            $event->longitude = $coords['longitude'];
        }
        $event->save();
        $ids[] = $event->id;
    }

    $this->comment("Skipped {$duplicates} duplicate events, {$skipped} other events.");
    $this->comment('Imported ' . count($ids) . ' events.');

    if ('y' !== strtolower($this->ask('Assign participants? Y/n'))) {
        return;
    }

    $participants = Participant::all();

    foreach ($ids as $id) {
        $event = Event::find($id);
        $this->comment($event);
        $this->comment($participants);
        $participants = explode(',', $this->ask("Participant IDs (<id1>,<id2>)"), $participants->count());
        $event->participants()->attach(array_map('intval', $participants));
    }
})->purpose('Import events from an ICS file');
