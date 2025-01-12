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
                    if (!isset($event['timezone'])) {
                        $event['timezone'] = 'America/Chicago';
                    }
                    $event['start_date'] = \dateFromTimezone($event['start_date'], $event['timezone']);
                    if (isset($event['end_date']) && $event['end_date']) {
                        $event['end_date'] = \dateFromTimezone($event['end_date'], $event['timezone']);
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
