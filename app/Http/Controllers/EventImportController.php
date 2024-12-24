<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Event;
use Inertia\Response;
use Illuminate\Http\Request;
use App\Services\File\IcsFileAdapter;

class EventImportController extends Controller
{
    public function show(): Response
    {
        return Inertia::render('Event/Import');
    }

    public function upload(Request $request, IcsFileAdapter $adapter)
    {
        $request->validate([
            'file' => 'required|file|mimes:ics',
        ]);

        $path = $request->file('file')->store('temp');

        $events = $adapter->getEvents($path);
        $events->each(fn(Event $event) => $event->save());

        return redirect()->back()->with('events', $events);
    }
}
