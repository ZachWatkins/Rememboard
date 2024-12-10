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
    public function run(): void
    {
        $path = database_path('seeders' . DIRECTORY_SEPARATOR . 'events*.json');
        // Match the file name with a wildcard.
        $matches = glob($path);
        if (empty($matches)) {
            $this->command->error('File not found: ' . $path);
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
                    // Apply the timezone offset to the start and end dates if present.
                    if (isset($event['timezone_offset'])) {
                        $event['start_date'] = \Carbon\Carbon::parse($event['start_date'])->addMinutes($event['timezone_offset']);
                        if (isset($event['end_date'])) {
                            $event['end_date'] = \Carbon\Carbon::parse($event['end_date'])->addMinutes($event['timezone_offset']);
                        }
                    } elseif (isset($event['timezone'])) {
                        $event['start_date'] = \Carbon\Carbon::parse($event['start_date'], $event['timezone']);
                        if (isset($event['end_date'])) {
                            $event['end_date'] = \Carbon\Carbon::parse($event['end_date'], $event['timezone']);
                        }
                    }
                    Event::create($event);
                    $created++;
                }
            }
            if ($skipped) {
                $this->command->warn($created . ' Events created and ' . $skipped . ' Events skipped.');
            } else {
                $this->command->info($created . ' Events created.');
            }
        }
    }
}
