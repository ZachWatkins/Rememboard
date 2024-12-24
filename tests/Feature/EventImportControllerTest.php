<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\User;
use App\Models\Event;

test('can upload .ics files', function () {
    $user = User::factory()->centralTz()->create();
    $fixture = base_path('tests/fixtures/events.ics');
    $name = 'temp.ics';
    $path = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $name;
    copy($fixture, $path);
    $file = new \Illuminate\Http\UploadedFile($path, $name, 'text/calendar', null, true);

    $response = $this->actingAs($user)->post('/events/import', ['file' => $file]);

    $response->assertRedirect();
    $response->assertSessionHas('events');
    $events = $response->session()->get('events');
    expect($events)->toBeInstanceOf(\Illuminate\Support\Collection::class);
    expect($events->count())->toBe(1);
    expect($events->first())->toBeInstanceOf(Event::class);
    expect($events->first()->name)->toBe('Test Event');
    expect($events->first()->start_date)->toBe((new \DateTime('2021-01-01 00:00:00', new \DateTimeZone('America/Chicago')))->setTimezone(new \DateTimeZone('UTC'))->format('Y-m-d H:i:s'));
    expect($events->first()->end_date)->toBe((new \DateTime('2021-01-01 01:00:00', new \DateTimeZone('America/Chicago')))->setTimezone(new \DateTimeZone('UTC'))->format('Y-m-d H:i:s'));
    expect($events->first()->address)->toBe('123 Main St');
});
