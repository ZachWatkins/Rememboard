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
        ]);

        $path = $request->file('file')->store('temp');

        $events = $adapter->getEvents($path);
        foreach ($events as $event) {
            $coordinates = $geolocationService->getCoordinates($event->address);
            try {
                if ($coordinates) {
                    $event->latitude = $coordinates['latitude'];
                    $event->longitude = $coordinates['longitude'];
                }
                $event->save();
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error($e->getMessage());
            }
        }

        return redirect()->back()->with('events', $events);
    }
}
