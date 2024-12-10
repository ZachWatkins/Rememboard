<?php

use App\Models\Event;
use Database\Seeders\EventSeeder;

it('imports a JSON file', function () {
    // Create a JSON file to import.
    $path = database_path('seeders' . DIRECTORY_SEPARATOR . 'events-test.json');

    // Create events with localized start_date and end_date values.
    $seeds = Event::factory()->count(4)->make();

    file_put_contents($path, json_encode($seeds->toArray()));

    // Seed the database.
    $seeder = new EventSeeder();
    $seeder->run();

    // Check that the events were created with UTC datetimes in the database.
    $events = Event::all();
    expect($events->count())->toBe(4);
});
