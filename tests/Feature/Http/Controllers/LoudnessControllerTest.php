<?php

namespace Tests\Feature\Http\Controllers;

use function Pest\Laravel\get;

test('show displays view', function (): void {

    $response = get(route('loudness.show'));

    $response->assertOk();
    $response->assertViewIs('loudness.show');
});
