<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;
use App\Services\GeolocationService;
use App\Services\File\IcsFileAdapter;
use Illuminate\Http\RedirectResponse;

class EventImportController extends Controller
{
    public function show(): Response
    {
        return Inertia::render('Event/Import');
    }

    public function upload(Request $request, IcsFileAdapter $adapter, GeolocationService $geolocationService): RedirectResponse
    {
        $request->validate([
            'file' => 'required|file|mimes:ics',
            'request_coordinates' => 'boolean',
        ]);

        $path = $request->file('file')->store('temp');

        $count = 0;

        foreach ($adapter->getEvents($path) as $event) {
            if (true === $request->input('request_coordinates') && $event->address) {
                $coords = $geolocationService->getCoordinates($event->address);
                $event->latitude = $coords['latitude'];
                $event->longitude = $coords['longitude'];
            }
            $event->save();
            $count++;
        }

        return redirect()->back()->with('count_imported', $count);
    }
}
