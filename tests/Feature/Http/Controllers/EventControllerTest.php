<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Event;
use Illuminate\Support\Carbon;
use function Pest\Faker\fake;
use function Pest\Laravel\assertModelMissing;
use function Pest\Laravel\delete;
use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\put;

uses(\JMac\Testing\Traits\AdditionalAssertions::class);

test('index displays view', function (): void {
    Event::factory()->count(3)->create();

    $response = get(route('events.index'));

    $response->assertOk();
    $response->assertViewIs('event.index');
    $response->assertViewHas('events');
});

test('index returns event dates as they are in the database', function (): void {
    $events = Event::factory()->count(3)->withEndDate()->create();

    $response = get(route('events.index'));

    $response->assertOk();
    $response->assertViewIs('event.index');
    $response->assertViewHas('events');

    $viewEvents = $response['events'];
    $viewEvents->each(function ($event) use ($events) {
        $stored = $events->firstWhere('id', $event->id);

        expect($event->start_date)->toBe($stored->start_date);
        expect($event->end_date)->toBe($stored->end_date);
    });
});

test('index returns event dates for user timezone', function (): void {
    $user = \App\Models\User::factory()->centralTz()->create();
    $events = Event::factory()->count(3)->withEndDate()->create();

    $response = $this->actingAs($user)->get(route('events.index'));

    $response->assertOk();
    $response->assertViewIs('event.index');
    $response->assertViewHas('events');

    $viewEvents = $response['events'];
    $viewEvents->each(function ($event) use ($events, $user) {
        $stored = $events->firstWhere('id', $event->id);

        expect($event->start_date)->not->toBe($stored->start_date);
        expect($event->start_date)->toBe(\dateToSessionTime($stored->start_date, $user));
        expect($event->end_date)->not->toBe($stored->end_date);
        expect($event->end_date)->toBe(\dateToSessionTime($stored->end_date, $user));
    });
});

test('create displays view', function (): void {
    $response = get(route('events.create'));

    $response->assertOk();
    $response->assertViewIs('event.create');
});

test('store uses form request validation')
    ->assertActionUsesFormRequest(
        \App\Http\Controllers\EventController::class,
        'store',
        \App\Http\Requests\EventStoreRequest::class
    );

test('store saves and redirects', function (): void {
    $name = fake()->name();
    $description = fake()->text();
    $start_date = Carbon::parse(fake()->dateTime());
    $latitude = fake()->latitude();
    $longitude = fake()->longitude();
    $city = fake()->city();
    $state = 'Texas';
    $folder_name = fake()->word();
    $show_on_countdown = fake()->boolean();
    $is_trip = fake()->boolean();

    $response = post(route('events.store'), [
        'name' => $name,
        'description' => $description,
        'start_date' => $start_date->toDateTimeString(),
        'latitude' => $latitude,
        'longitude' => $longitude,
        'city' => $city,
        'state' => $state,
        'folder_name' => $folder_name,
        'show_on_countdown' => $show_on_countdown,
        'is_trip' => $is_trip,
    ]);
    $response->assertSessionHasNoErrors();
    $events = Event::query()
        ->where('name', $name)
        ->where('description', $description)
        ->where('start_date', $start_date)
        ->where('latitude', $latitude)
        ->where('longitude', $longitude)
        ->where('city', $city)
        ->where('state', $state)
        ->where('folder_name', $folder_name)
        ->where('show_on_countdown', $show_on_countdown)
        ->where('is_trip', $is_trip)
        ->get();
    expect($events)->toHaveCount(1);
    $event = $events->first();

    $response->assertRedirect(route('events.index'));
    $response->assertSessionHas('event.id', $event->id);
});

test('show displays view', function (): void {
    $event = Event::factory()->create();

    $response = get(route('events.show', $event));

    $response->assertOk();
    $response->assertViewIs('event.show');
    $response->assertViewHas('event');
});

test('show returns event dates as they are in the database', function (): void {
    $event = Event::factory()->withEndDate()->create();

    $response = get(route('events.show', $event));

    $response->assertOk();
    $response->assertViewIs('event.show');
    $response->assertViewHas('event');

    $viewEvent = $response['event'];
    expect($viewEvent->start_date)->toBe($event->start_date);
    expect($viewEvent->end_date)->toBe($event->end_date);
});

test('show returns event dates for user timezone', function (): void {
    $user = \App\Models\User::factory()->centralTz()->create();
    $event = Event::factory()->withEndDate()->create();

    $response = $this->actingAs($user)->get(route('events.show', $event));

    $response->assertOk();
    $response->assertViewIs('event.show');
    $response->assertViewHas('event');

    $viewEvent = $response['event'];
    expect($viewEvent->start_date)->toBe((new \DateTime($event->start_date, new \DateTimeZone('UTC')))->setTimezone(new \DateTimeZone($user->timezone))->format('Y-m-d H:i:s'));
    expect($viewEvent->end_date)->toBe((new \DateTime($event->end_date, new \DateTimeZone('UTC')))->setTimezone(new \DateTimeZone($user->timezone))->format('Y-m-d H:i:s'));
});

test('edit displays view', function (): void {
    $event = Event::factory()->create();

    $response = get(route('events.edit', $event));

    $response->assertOk();
    $response->assertViewIs('event.edit');
    $response->assertViewHas('event');
});

test('update uses form request validation')
    ->assertActionUsesFormRequest(
        \App\Http\Controllers\EventController::class,
        'update',
        \App\Http\Requests\EventUpdateRequest::class
    );

test('update redirects', function (): void {
    $event = Event::factory()->create();
    $name = fake()->name();
    $description = fake()->text();
    $start_date = Carbon::parse(fake()->dateTime());
    $latitude = fake()->latitude();
    $longitude = fake()->longitude();
    $city = fake()->city();
    $state = 'Texas';
    $folder_name = fake()->word();
    $show_on_countdown = fake()->boolean();
    $is_trip = fake()->boolean();

    $response = put(route('events.update', $event), [
        'name' => $name,
        'description' => $description,
        'start_date' => $start_date->toDateTimeString(),
        'latitude' => $latitude,
        'longitude' => $longitude,
        'city' => $city,
        'state' => $state,
        'folder_name' => $folder_name,
        'show_on_countdown' => $show_on_countdown,
        'is_trip' => $is_trip,
    ]);

    $event->refresh();

    $response->assertRedirect(route('events.index'));
    $response->assertSessionHas('event.id', $event->id);

    expect($name)->toEqual($event->name);
    expect($description)->toEqual($event->description);
    expect($start_date)->toEqual($event->start_date);
    expect($latitude)->toEqual($event->latitude);
    expect($longitude)->toEqual($event->longitude);
    expect($city)->toEqual($event->city);
    expect($state)->toEqual($event->state);
    expect($folder_name)->toEqual($event->folder_name);
    expect($show_on_countdown)->toEqual($event->show_on_countdown);
    expect($is_trip)->toEqual($event->is_trip);
});

test('destroy deletes and redirects', function (): void {
    $event = Event::factory()->create();

    $response = delete(route('events.destroy', $event));

    $response->assertRedirect(route('events.index'));

    assertModelMissing($event);
});

test('countdowns displays view', function (): void {
    $events = Event::factory()->count(3)->create();

    $response = get(route('events.countdowns'));

    $response->assertOk();
    $response->assertViewIs('event.index');
    $response->assertViewHas('events');
});

test('trips responds with', function (): void {
    $trips = Event::factory()->count(3)->create(['is_trip' => true]);

    $response = get(route('events.trips'));

    $response->assertOk();
    $response->assertJson($trips->toArray());
});

test('store converts user timezone dates to UTC', function (): void {
    $user = \App\Models\User::factory()->centralTz()->create();
    $submitted = Event::factory()->withEndDate()->make();

    $response = $this->actingAs($user)->post(route('events.store'), $submitted->toArray());

    $response->assertSessionHasNoErrors();
    $response->assertRedirect(route('events.index'));

    $eventId = session('event.id');
    $response->assertSessionHas('event.id', $eventId);
    $stored = Event::find($eventId);

    expect($stored->start_date)->not->toBe($submitted->start_date);
    expect($stored->start_date)->toBe((new \DateTime($submitted->start_date, new \DateTimeZone($user->timezone)))->setTimezone(new \DateTimeZone('UTC'))->format('Y-m-d H:i:s'));

    expect($stored->end_date)->not->toBe($submitted->end_date);
    expect($stored->end_date)->toBe((new \DateTime($submitted->end_date, new \DateTimeZone($user->timezone)))->setTimezone(new \DateTimeZone('UTC'))->format('Y-m-d H:i:s'));
});

test('update converts user timezone dates to UTC', function (): void {
    $user = \App\Models\User::factory()->centralTz()->create();
    $submitted = Event::factory()->withEndDate()->create();
    $newStartDate = (new \DateTime($submitted->start_date, new \DateTimeZone($user->timezone)))->modify('-1 day')->format('Y-m-d H:i:s');
    $newEndDate = (new \DateTime($submitted->end_date, new \DateTimeZone($user->timezone)))->modify('-1 day')->format('Y-m-d H:i:s');

    $response = $this->actingAs($user)->patch(route('events.update', $submitted), array_merge($submitted->toArray(), [
        'start_date' => $newStartDate,
        'end_date' => $newEndDate,
    ]));

    $response->assertSessionHasNoErrors();
    $response->assertRedirect(route('events.index'));

    $eventId = session('event.id');
    $response->assertSessionHas('event.id', $eventId);
    $stored = Event::find($eventId);

    expect($stored->start_date)->not->toBe($submitted->start_date);
    expect($stored->start_date)->not->toBe($newStartDate);
    expect($stored->start_date)->toBe((new \DateTime($newStartDate, new \DateTimeZone($user->timezone)))->setTimezone(new \DateTimeZone('UTC'))->format('Y-m-d H:i:s'));

    expect($stored->end_date)->not->toBe($submitted->end_date);
    expect($stored->end_date)->not->toBe($newEndDate);
    expect($stored->end_date)->toBe((new \DateTime($newEndDate, new \DateTimeZone($user->timezone)))->setTimezone(new \DateTimeZone('UTC'))->format('Y-m-d H:i:s'));
});
