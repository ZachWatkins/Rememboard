<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\Participant;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class EventSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(?string $path = null): void
    {
        if (null === $path) {
            $matches = glob(database_path('seeders' . DIRECTORY_SEPARATOR . 'events.json'));
            if (empty($matches)) {
                return;
            }
        } else {
            $matches = glob($path);
            if (empty($matches)) {
                $this->command?->comment('File not found: ' . $path);
                return;
            }
        }
        $participants = Participant::all();
        foreach ($matches as $path) {
            $events = json_decode(\file_get_contents($path), true);
            $skipped = 0;
            $created = 0;
            foreach ($events as $event) {
                if (Event::where('name', $event['name'])->exists()) {
                    $skipped++;
                } else {
                    // If the data includes a timezone attribute, convert the start_date and end_date to UTC from the given timezone.
                    if (isset($event['timezone'])) {
                        $event['start_date'] = (new \DateTime($event['start_date'], new \DateTimeZone($event['timezone'])))->setTimezone(new \DateTimeZone('UTC'))->format('Y-m-d H:i:s');
                        if (isset($event['end_date']) && $event['end_date']) {
                            $event['end_date'] = (new \DateTime($event['end_date'], new \DateTimeZone($event['timezone'])))->setTimezone(new \DateTimeZone('UTC'))->format('Y-m-d H:i:s');
                        }
                        unset($event['timezone']);
                    }
                    $eventParticipants = $event['participants'] ?? [];
                    if (isset($event['participants'])) {
                        unset($event['participants']);
                    }
                    Event::create($event)->participants()->sync($participants->whereIn('name', $eventParticipants)->pluck('id'));
                    $created++;
                }
            }
            if ($skipped) {
                $this->command?->warn($created . ' Events created and ' . $skipped . ' Events skipped.');
            } else {
                $this->command?->info($created . ' Events created.');
            }
        }
    }
}
