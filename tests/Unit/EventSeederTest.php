<?php

use App\Models\Event;
use Database\Seeders\EventSeeder;

it('imports a JSON file', function () {
    $path = database_path('seeders' . DIRECTORY_SEPARATOR . 'events-test.json');
    $seeds = Event::factory()->count(4)->make();

    file_put_contents($path, json_encode($seeds->toArray()));

    $seeder = new EventSeeder();
    $seeder->run();
    $events = Event::all();

    expect($events->count())->toBe(4);
});
