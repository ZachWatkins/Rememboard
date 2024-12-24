<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Event;
use Inertia\Response;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\EventStoreRequest;
use App\Http\Requests\EventUpdateRequest;

class EventController extends Controller
{
    public function index(Request $request): Response
    {
        $events = Event::all();

        // If a user is logged in, convert the start_date and end_date to the user's timezone.
        $events->each(function ($event) use ($request) {
            $event->start_date = \dateToSessionTime($event->start_date, $request->user());
            $event->end_date = \dateToSessionTime($event->end_date, $request->user());
        });

        return Inertia::render('Event/Index', [
            'events' => $events,
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Event/Create');
    }

    public function store(EventStoreRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['start_date'] = \dateFromSessionTime($validated['start_date'], $request->user());
        if (isset($validated['end_date'])) {
            $validated['end_date'] = \dateFromSessionTime($validated['end_date'], $request->user());
        }
        $event = Event::create($validated);

        $request->session()->flash('event.id', $event->id);

        return redirect()->route('events.index');
    }

    public function show(Request $request, Event $event): Response
    {
        $event->start_date = \dateToSessionTime($event->start_date, $request->user());
        $event->end_date = \dateToSessionTime($event->end_date, $request->user());
        return Inertia::render('Event/Show', [
            'event' => $event,
        ]);
    }

    public function edit(Request $request, Event $event): Response
    {
        return Inertia::render('Event/Edit', [
            'event' => $event,
        ]);
    }

    public function update(EventUpdateRequest $request, Event $event): RedirectResponse
    {
        $validated = $request->validated();
        $validated['start_date'] = \dateFromSessionTime($validated['start_date'], $request->user());
        if (isset($validated['end_date'])) {
            $validated['end_date'] = \dateFromSessionTime($validated['end_date'], $request->user());
        }
        $event->update($validated);

        $request->session()->flash('event.id', $event->id);

        return redirect()->route('events.index');
    }

    public function destroy(Request $request, Event $event): RedirectResponse
    {
        $event->delete();

        return redirect()->route('events.index');
    }

    public function countdowns(Request $request): Response
    {
        $events = Event::where('show_on_countdown', true)->orderBy('start_date')->get();
        $events->each(function ($event) use ($request) {
            $event->start_date = \dateToSessionTime($event->start_date, $request->user());
            $event->end_date = \dateToSessionTime($event->end_date, $request->user());
        });

        return Inertia::render('Event/Countdowns', [
            'events' => $events,
        ]);
    }

    public function trips(Request $request): Response
    {
        $events = Event::where('is_trip', true)->get();
        $events->each(function ($event) use ($request) {
            $event->start_date = \dateToSessionTime($event->start_date, $request->user());
            $event->end_date = \dateToSessionTime($event->end_date, $request->user());
        });

        return Inertia::render('Event/Trips', [
            'events' => $events,
        ]);
    }
}
