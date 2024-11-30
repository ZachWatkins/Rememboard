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
    $events = Event::factory()->count(3)->create();

    $response = get(route('events.index'));

    $response->assertOk();
    $response->assertViewIs('event.index');
    $response->assertViewHas('events');
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
    $trips = Event::factory()->count(3)->create();

    $response = get(route('events.trips'));

    $response->assertOk();
    $response->assertJson($trips);
});
