<?php

use App\Models\Event;
use Database\Seeders\EventSeeder;

afterAll(function () {
    if (file_exists(__DIR__ . '/../../database/seeders/events-test.json')) {
        unlink(__DIR__ . '/../../database/seeders/events-test.json');
    }
    if (file_exists(__DIR__ . '/../../database/seeders/events-test-timezone.json')) {
        unlink(__DIR__ . '/../../database/seeders/events-test-timezone.json');
    }
});

it('imports a JSON file', function () {
    $path = database_path('seeders' . DIRECTORY_SEPARATOR . 'events-test.json');
    $seeds = Event::factory()->count(4)->make();

    expect(Event::count())->toBe(0);

    file_put_contents($path, json_encode($seeds->toArray()));
    (new EventSeeder())->run($path);

    $events = Event::all();

    expect($events->count())->toBe(4);
});

it('Converts start_date and end_date from CST to UTC', function () {
    $path = database_path('seeders' . DIRECTORY_SEPARATOR . 'events-test-timezone.json');
    $seeds = Event::factory()->count(4)->make();

    $seeds = $seeds->map(function ($seed) {
        $seed->timezone = 'America/Chicago'; // CST.
        return $seed;
    });

    file_put_contents($path, json_encode($seeds));

    (new EventSeeder())->run($path);
    $events = Event::all();

    // Expect the file's CST dates to be correctly converted to UTC once seeded.
    $events->each(function ($event, $index) use ($seeds) {
        expect($event->start_date)->not->toBe((new \DateTime($seeds[$index]->start_date))->format('Y-m-d\TH:i:s'));
        expect($event->start_date)->toBe((new \DateTime($event->start_date))->setTimezone(new \DateTimeZone('UTC'))->format('Y-m-d\TH:i:s'));
    });
});
