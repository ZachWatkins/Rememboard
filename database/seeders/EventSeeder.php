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
            foreach ($events as $event) {
                Event::create($event);
            }
            $this->command->info( count($events) . ' Events created!');
        } else {
            $this->command->error('File not found: ' . $path);
        }
    }
}
