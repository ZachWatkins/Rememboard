<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function show(Request $request): Response
    {
        return Inertia::render('Dashboard', [
            'events' => \App\Models\Event::all(),
        ]);
    }
}
