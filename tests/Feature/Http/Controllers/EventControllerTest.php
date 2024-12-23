<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\User;
use App\Models\Event;
use Illuminate\Support\Carbon;
use Inertia\Testing\AssertableInertia as Assert;
use function Pest\Faker\fake;
use function Pest\Laravel\assertModelMissing;
use function Pest\Laravel\delete;
use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\put;

uses(\JMac\Testing\Traits\AdditionalAssertions::class);

test('index displays view', function (): void {
    Event::factory()->count(3)->create();
    $user = User::factory()->create();

    $this->actingAs($user)->get(route('events.index'))
        ->assertInertia(
            fn(Assert $page) => $page
                ->component('Event/Index')
                ->has('events', 3)
        );
});

test('index returns event dates for user timezone', function (): void {
    $user = \App\Models\User::factory()->centralTz()->create();
    $events = Event::factory()->count(3)->withEndDate()->create();

    $this->actingAs($user)->get(route('events.index'))
        ->assertInertia(
            fn(Assert $page) => $page
                ->component('Event/Index')
                ->has('events', 3)
        );

    // $viewEvents = $response['events'];
    // $viewEvents->each(function ($event) use ($events, $user) {
    //     $stored = $events->firstWhere('id', $event->id);

    //     expect($event->start_date)->not->toBe($stored->start_date);
    //     expect($event->start_date)->toBe(\dateToSessionTime($stored->start_date, $user));
    //     expect($event->end_date)->not->toBe($stored->end_date);
    //     expect($event->end_date)->toBe(\dateToSessionTime($stored->end_date, $user));
    // });
});

test('create displays view', function (): void {
    $events = Event::factory()->count(3)->create();
    $user = User::factory()->create();
    $this->actingAs($user)->get(route('events.create'))
        ->assertInertia(
            fn(Assert $page) => $page
                ->component('Event/Create')
        );
});

test('store saves and redirects', function (): void {
    $user = User::factory()->centralTz()->create();
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
    $response = $this->actingAs($user)->post(route('events.store'), [
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
    $response->assertSessionHas('event.id', 1);

    $event = Event::query()
        ->where('name', $name)
        ->where('description', $description)
        ->where('latitude', $latitude)
        ->where('longitude', $longitude)
        ->where('city', $city)
        ->where('state', $state)
        ->where('folder_name', $folder_name)
        ->where('show_on_countdown', $show_on_countdown)
        ->where('is_trip', $is_trip)
        ->first();
    expect($event)->not->toBeNull();

    $response->assertRedirect(route('events.index'));
});

test('store uses form request validation')
    ->assertActionUsesFormRequest(
        \App\Http\Controllers\EventController::class,
        'store',
        \App\Http\Requests\EventStoreRequest::class
    );

test('store converts user timezone dates to UTC', function (): void {
    $user = User::factory()->centralTz()->create();
    $event = Event::factory()->make();
    $response = $this->actingAs($user)->post(route('events.store'), $event->toArray());
    $response->assertSessionHasNoErrors();
    $response->assertSessionHas('event.id', 1);

    $stored = Event::find(session('event.id'));
    expect($stored)->not->toBeNull();
    expect($stored->start_date)->not->toBe($event->start_date);
    expect($stored->start_date)->toBe(\dateFromSessionTime($event->start_date, $user));
});

test('show displays view', function (): void {
    $event = Event::factory()->create();
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('events.show', $event));

    $response->assertOk();
    $response->assertInertia(
        fn(Assert $page) => $page
            ->component('Event/Show')
            ->has('event')
    );
});

test('show returns event dates for user timezone', function (): void {
    $user = \App\Models\User::factory()->centralTz()->create();
    $event = Event::factory()->withEndDate()->create();

    $response = $this->actingAs($user)->get(route('events.show', $event));

    $response->assertOk();
    $response->assertInertia(
        fn(Assert $page) => $page
            ->component('Event/Show')
            ->has(
                'event',
                fn(Assert $page) => $page
                    ->where('id', $event->id)
                    ->where('name', $event->name)
                    ->where('description', $event->description)
                    ->where('start_date', \dateToSessionTime($event->start_date, $user))
                    ->where('end_date', \dateToSessionTime($event->end_date, $user))
                    ->where('latitude', $event->latitude)
                    ->where('longitude', $event->longitude)
                    ->where('city', $event->city)
                    ->where('state', $event->state)
                    ->where('folder_name', $event->folder_name)
                    ->where('show_on_countdown', $event->show_on_countdown)
                    ->where('is_trip', $event->is_trip)
                    ->etc()
            )
    );
});

test('edit displays view', function (): void {
    $user = User::factory()->centralTz()->create();
    $event = Event::factory()->create();

    $response = $this->actingAs($user)->get(route('events.edit', $event));
    $response->assertOk();
    $response->assertInertia(
        fn(Assert $page) => $page
            ->component('Event/Edit')
            ->has(
                'event',
                fn(Assert $page) => $page
                    ->where('id', $event->id)
                    ->where('name', $event->name)
                    ->where('description', $event->description)
                    ->where('start_date', $event->start_date)
                    ->where('end_date', $event->end_date)
                    ->where('latitude', $event->latitude)
                    ->where('longitude', $event->longitude)
                    ->where('city', $event->city)
                    ->where('state', $event->state)
                    ->where('folder_name', $event->folder_name)
                    ->where('show_on_countdown', $event->show_on_countdown)
                    ->where('is_trip', $event->is_trip)
                    ->etc()
            )
    );
});

test('update uses form request validation')
    ->assertActionUsesFormRequest(
        \App\Http\Controllers\EventController::class,
        'update',
        \App\Http\Requests\EventUpdateRequest::class
    );

test('update redirects', function (): void {
    $user = User::factory()->centralTz()->create();
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

    $response = $this->actingAs($user)->put(route('events.update', $event), [
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
    expect($start_date)->toEqual(\dateToSessionTime($event->start_date, $user));
    expect($latitude)->toEqual($event->latitude);
    expect($longitude)->toEqual($event->longitude);
    expect($city)->toEqual($event->city);
    expect($state)->toEqual($event->state);
    expect($folder_name)->toEqual($event->folder_name);
    expect($show_on_countdown)->toEqual($event->show_on_countdown);
    expect($is_trip)->toEqual($event->is_trip);
});

test('destroy deletes and redirects', function (): void {
    $user = User::factory()->centralTz()->create();
    $event = Event::factory()->create();

    $response = $this->actingAs($user)->delete(route('events.destroy', $event));

    $response->assertRedirect(route('events.index'));

    assertModelMissing($event);
});

test('countdowns displays view', function (): void {
    $user = User::factory()->centralTz()->create();
    $events = Event::factory()->count(3)->create();

    $response = $this->actingAs($user)->get(route('events.countdowns'));
    $response->assertOk();
    $response->assertInertia(
        fn(Assert $page) => $page
            ->component('Event/Countdowns')
            ->has('events', $events->where('show_on_countdown', true)->count())
    );
});

test('trips responds with', function (): void {
    $user = User::factory()->create();
    $events = Event::factory()->count(3)->create();

    // $response = get(route('events.trips'));
    $response = $this->actingAs($user)->get(route('events.trips'));

    $response->assertOk();
    $response->assertInertia(
        fn(Assert $page) => $page
            ->component('Event/Trips')
            ->has('events', $events->where('is_trip', true)->count())
    );
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
    expect($stored->start_date)->toBe(\dateFromSessionTime($newStartDate, $user));

    expect($stored->end_date)->not->toBe($submitted->end_date);
    expect($stored->end_date)->not->toBe($newEndDate);
    expect($stored->end_date)->toBe(\dateFromSessionTime($newEndDate, $user));
});
