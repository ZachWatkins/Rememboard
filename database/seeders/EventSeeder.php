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
        $path = database_path('seeders' . DIRECTORY_SEPARATOR . 'events.json');
        if (\file_exists($path)) {
            $events = json_decode(\file_get_contents($path), true);
            $skipped = 0;
            $created = 0;
            foreach ($events as $event) {
                if (Event::where('name', $event['name'])->exists()) {
                    $skipped++;
                } else {
                    $created++;
                    Event::create($event);
                }
            }
            if ($skipped) {
                $this->command->warn($created . ' Events created and ' . $skipped . ' Events skipped.');
            } else {
                $this->command->info($created . ' Events created.');
            }
        } else {
            $this->command->error('File not found: ' . $path);
        }
    }
}
