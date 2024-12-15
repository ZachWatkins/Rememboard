<?php

namespace Database\Seeders;

use App\Models\Event;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class EventSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(string $path = ''): void
    {
        if (!$path) {
            $path = database_path('seeders' . DIRECTORY_SEPARATOR . 'events*.json');
        }
        $matches = glob($path);
        if (empty($matches)) {
            $this->command?->error('File not found: ' . $path);
            return;
        }
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
                        $event['start_date'] = (new \DateTime($event['start_date'], new \DateTimeZone($event['timezone'])))->setTimezone(new \DateTimeZone('UTC'))->format('Y-m-d\TH:i:s');
                        if (isset($event['end_date']) && $event['end_date']) {
                            $event['end_date'] = (new \DateTime($event['end_date'], new \DateTimeZone($event['timezone'])))->setTimezone(new \DateTimeZone('UTC'))->format('Y-m-d\TH:i:s');
                        }
                        unset($event['timezone']);
                    }
                    Event::create($event);
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
