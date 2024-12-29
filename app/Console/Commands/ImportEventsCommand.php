<?php

namespace App\Console\Commands;

use App\Models\Event;
use App\Models\Participant;
use Illuminate\Console\Command;
use App\Services\GeolocationService;
use App\Services\File\IcsFileAdapter;

class ImportEventsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:import-events';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import events from a .ics file';

    /**
     * Execute the console command.
     */
    public function handle(IcsFileAdapter $adapter, GeolocationService $geolocationService)
    {
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

        $this->comment("Skipped {$duplicates} duplicate events and {$skipped} other events.");
        $this->comment('Imported ' . count($ids) . ' events.');

        if ('y' !== strtolower($this->ask('Assign participants? Y/n'))) {
            return;
        }

        $participants = Participant::all();
        $participantQuestion = 'Participant IDs (' . $participants->map(fn($item) => "<{$item->id}:{$item->name}>")->join(',') . ')';

        foreach ($ids as $id) {
            $event = Event::find($id);
            $this->comment($event);
            $event->participants()->sync(array_map('intval', explode(',', $this->ask($participantQuestion, $participants->count()))));
        }
    }
}
