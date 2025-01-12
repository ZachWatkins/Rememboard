<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;
use App\Services\GeolocationService;
use App\Services\File\IcsFileAdapter;
use Illuminate\Http\RedirectResponse;
use App\Services\AddressParsingService;

class CalendarController extends Controller
{
    public function show(): Response
    {
        return Inertia::render('Calendar');
    }

    public function import(): Response
    {
        return Inertia::render('Calendar/Import');
    }

    public function store(Request $request, IcsFileAdapter $adapter, GeolocationService $geolocationService, AddressParsingService $addressParser): RedirectResponse
    {
        $request->validate([
            'file' => 'required|file|mimes:ics',
            'request_coordinates' => 'boolean',
        ]);

        $path = $request->file('file')->store('temp');

        $count = 0;

        $events = collect();
        foreach ($adapter->getEvents($path, $addressParser) as $event) {
            if (true === $request->input('request_coordinates') && $event->address) {
                $coords = $geolocationService->getCoordinates($event->address);
                $event->latitude = $coords['latitude'];
                $event->longitude = $coords['longitude'];
            }
            $event->start_date = \dateFromTimezone($event->start_date, $event->timezone);
            if ($event->end_date) {
                $event->end_date = \dateFromTimezone($event->end_date, $event->timezone);
            }
            $event->save();
            $events->push($event);
            $count++;
        }

        $request->session()->flash('events', $events);

        return redirect()->back()->with('count_imported', $count);
    }
}
