<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Event;
use function Pest\Laravel\get;

test('countdowns displays view', function (): void {
    $events = Event::factory()->count(3)->create();

    $response = get(route('events.countdowns'));

    $response->assertOk();
    $response->assertViewIs('event.index');
    $response->assertViewHas('events');
});


test('trips responds with', function (): void {
    $events = Event::factory()->count(3)->create();

    $response = get(route('events.trips'));

    $response->assertOk();
    $response->assertJson($trips);
});
