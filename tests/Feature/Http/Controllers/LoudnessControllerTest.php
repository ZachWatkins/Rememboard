<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Loudness;
use function Pest\Laravel\get;

test('show displays view', function (): void {
    $loudness = Loudness::factory()->create();

    $response = get(route('loudnesses.show', $loudness));

    $response->assertOk();
    $response->assertViewIs('loudness.show');
});
