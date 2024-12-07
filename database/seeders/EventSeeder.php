<?php

namespace Database\Seeders;

use App\Models\Event;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class EventSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (Storage::exists(database_path('seeders/events.json'))) {
            $events = json_decode(Storage::get(database_path('seeders/events.json')), true);

            foreach ($events as $event) {
                Event::create($event);
            }
        } else {
            Log::info('File not found: ' . database_path('seeders/events.json'));
        }
    }
}
