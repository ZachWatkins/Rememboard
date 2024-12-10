<?php

use App\Models\Event;
use Database\Seeders\EventSeeder;

it('applies timezones to imported events', function () {
    // Create a JSON file to import.
    $path = database_path('seeders' . DIRECTORY_SEPARATOR . 'events-test.json');

    // Create events with localized start_date and end_date values.
    $seeds = [
        Event::factory()->eastern()->make()->toArray(),
        Event::factory()->pacific()->make()->toArray(),
        Event::factory()->mountain()->make()->toArray(),
        Event::factory()->central()->make()->toArray(),
    ];

    file_put_contents($path, json_encode($seeds));

    // Seed the database.
    $seeder = new EventSeeder();
    $seeder->run();

    // Check that the events were created with UTC datetimes in the database.
    $events = Event::all();
    expect($events->count())->toBe(4);
});
