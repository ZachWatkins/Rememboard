<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Event;
use Inertia\Response;

class EventImportController extends Controller
{
    public function show(): Response
    {
        return Inertia::render('Event/Import');
    }

    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:ical,txt',
        ]);

        $path = $request->file('file')->store('temp');

        $events = Event::import($path);

        return redirect()->back()->with('events', $events);
    }
}
